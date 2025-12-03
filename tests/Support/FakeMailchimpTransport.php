<?php

namespace Nails\MailChimp\Tests\Support;

use Nails\MailChimp\Exception\Api\ApiException;
use stdClass;

/**
 * A simple in-memory simulator for Mailchimp endpoints used in tests.
 */
class FakeMailchimpTransport
{
    private array $lists   = [];
    private array $members = []; // [listId][memberHash] => member array

    public function __invoke(string $method, string $endpoint, array $params): ?stdClass
    {
        // Normalise
        $endpoint = trim($endpoint, '/');

        // Lists collection
        if ($endpoint === 'lists') {
            if ($method === 'GET') {
                return $this->json([
                    'lists' => array_values(array_map(function ($list) {
                        return (object) $list;
                    }, $this->lists)),
                ]);
            }
            if ($method === 'POST') {
                $id               = $this->uuid();
                $name             = $params['name'] ?? 'Test List';
                $list             = [
                    'id'                  => $id,
                    'web_id'              => 0,
                    'name'                => $name,
                    'contact'             => (object) ($params['contact'] ?? new stdClass()),
                    'permission_reminder' => $params['permission_reminder'] ?? '',
                    'campaign_defaults'   => (object) ($params['campaign_defaults'] ?? new stdClass()),
                    'email_type_option'   => (bool) ($params['email_type_option'] ?? false),
                    'modules'             => [],
                    'stats'               => (object) [],
                    '_links'              => [],
                    'date_created'        => date('c'),
                ];
                $this->lists[$id] = $list;
                return (object) $list;
            }
        }

        // List specific
        if (preg_match('#^lists/([^/]+)$#', $endpoint, $m)) {
            $listId = $m[1];
            if (!isset($this->lists[$listId])) {
                throw new ApiException('Resource Not Found');
            }
            if ($method === 'GET') {
                return (object) $this->lists[$listId];
            }
            if ($method === 'PATCH') {
                // Only name is used by tests
                if (isset($params['name'])) {
                    $this->lists[$listId]['name'] = $params['name'];
                }
                return (object) $this->lists[$listId];
            }
            if ($method === 'DELETE') {
                unset($this->lists[$listId]);
                unset($this->members[$listId]);
                return null;
            }
        }

        // Members collection
        if (preg_match('#^lists/([^/]+)/members$#', $endpoint, $m)) {
            $listId = $m[1];
            $this->assertList($listId);
            if ($method === 'GET') {
                $members = isset($this->members[$listId]) ? array_values(array_map(function ($m) {
                    return (object) $m;
                }, $this->members[$listId])) : [];
                return $this->json(['members' => $members]);
            }
            if ($method === 'POST') {
                $email = $params['email_address'] ?? null;
                if (empty($email)) {
                    throw new ApiException('Invalid email');
                }
                $hash             = md5(strtolower(trim($email)));
                $member           = $this->buildMember($listId, $email);
                $member['status'] = $params['status'] ?? 'subscribed';
                if (isset($params['email_type'])) {
                    $member['email_type'] = $params['email_type'];
                }
                $this->members[$listId][$hash] = $member;
                return (object) $member;
            }
        }

        // Member specific and actions
        if (preg_match('#^lists/([^/]+)/members/([^/]+)(.*)$#', $endpoint, $m)) {
            $listId = $m[1];
            $hash   = $m[2];
            $suffix = $m[3];
            $this->assertList($listId);
            if (!isset($this->members[$listId][$hash])) {
                if ($method === 'GET' || $method === 'PATCH' || $method === 'DELETE') {
                    throw new ApiException('Resource Not Found');
                }
            }

            if ($suffix === '') {
                if ($method === 'GET') {
                    return (object) $this->members[$listId][$hash];
                }
                if ($method === 'PATCH') {
                    // Update allowed fields
                    foreach (['status', 'email_type'] as $field) {
                        if (array_key_exists($field, $params)) {
                            $this->members[$listId][$hash][$field] = $params[$field];
                        }
                    }
                    return (object) $this->members[$listId][$hash];
                }
                if ($method === 'DELETE') {
                    // archive: mark the member as archived but keep the record
                    $this->members[$listId][$hash]['status'] = 'archived';
                    return null;
                }
            }

            if ($suffix === '/actions/delete-permanent' && $method === 'POST') {
                unset($this->members[$listId][$hash]);
                return null;
            }

            if ($suffix === '/tags') {
                if ($method === 'POST') {
                    $tags = $params['tags'] ?? [];
                    foreach ($tags as $t) {
                        $name   = $t['name'] ?? '';
                        $status = $t['status'] ?? 'active';
                        if ($name === '') {
                            continue;
                        }
                        $existing = $this->members[$listId][$hash]['tags'] ?? [];
                        // Update or insert
                        $existing = array_filter($existing, function ($et) use ($name) {
                            return $et['name'] !== $name;
                        });
                        if ($status === 'active') {
                            $existing[] = ['name' => $name, 'status' => 'active'];
                        }
                        $this->members[$listId][$hash]['tags'] = $existing;
                    }
                    return null;
                }
                if ($method === 'GET') {
                    $tags = array_map(function ($t) {
                        return (object) $t;
                    }, $this->members[$listId][$hash]['tags'] ?? []);
                    return $this->json(['tags' => $tags]);
                }
            }
        }

        throw new ApiException('Unhandled endpoint: ' . $method . ' ' . $endpoint);
    }

    private function assertList(string $listId): void
    {
        if (!isset($this->lists[$listId])) {
            throw new ApiException('Resource Not Found');
        }
    }

    private function buildMember(string $listId, string $email): array
    {
        return [
            'id'               => $this->uuid(),
            'email_address'    => $email,
            'unique_email_id'  => substr($this->uuid(), 0, 10),
            'web_id'           => 0,
            'email_type'       => 'html',
            'status'           => 'subscribed',
            'merge_fields'     => (object) [],
            'stats'            => (object) [],
            'ip_signup'        => '',
            'timestamp_signup' => null,
            'ip_opt'           => '',
            'timestamp_opt'    => null,
            'member_rating'    => 0,
            'last_changed'     => date('c'),
            'language'         => '',
            'vip'              => false,
            'email_client'     => '',
            'location'         => (object) [],
            'source'           => '',
            'tags_count'       => 0,
            'tags'             => [],
            'list_id'          => $listId,
            '_links'           => [],
        ];
    }

    private function json(array $data): stdClass
    {
        return json_decode(json_encode($data));
    }

    private function uuid(): string
    {
        // Simple unique ID for tests
        return bin2hex(random_bytes(8));
    }
}
