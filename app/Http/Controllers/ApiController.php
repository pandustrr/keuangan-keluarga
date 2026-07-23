<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    /**
     * Auth: Login with password
     */
    public function login(Request $request)
    {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');

        if (empty($email) || empty($password)) {
            return response()->json([
                'session' => null,
                'error' => ['message' => 'Email dan password wajib diisi.']
            ]);
        }

        $user = DB::table('allowed_emails')->where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'session' => null,
                'error' => ['message' => 'Akun belum terdaftar di sistem.']
            ]);
        }

        // Verify or initialize password
        if (empty($user->password)) {
            // First time login with set password
            DB::table('allowed_emails')->where('email', $email)->update([
                'password' => Hash::make($password),
                'updated_at' => now(),
            ]);
        } else {
            if (!Hash::check($password, $user->password)) {
                return response()->json([
                    'session' => null,
                    'error' => ['message' => 'Password salah.']
                ]);
            }
        }

        Session::put('user_email', $email);

        return response()->json([
            'session' => [
                'user' => [
                    'id' => (string) $user->id,
                    'email' => $user->email,
                ]
            ],
            'error' => null
        ]);
    }

    /**
     * Auth: Register
     */
    public function register(Request $request)
    {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');

        if (empty($email) || strlen($password) < 6) {
            return response()->json([
                'session' => null,
                'error' => ['message' => 'Email & password (minimal 6 karakter) wajib diisi.']
            ]);
        }

        $user = DB::table('allowed_emails')->where('email', $email)->first();

        if ($user) {
            if (!empty($user->password) && Hash::check($password, $user->password)) {
                Session::put('user_email', $email);
                return response()->json([
                    'session' => ['user' => ['email' => $email]],
                    'error' => null
                ]);
            }
            DB::table('allowed_emails')->where('email', $email)->update([
                'password' => Hash::make($password),
                'updated_at' => now(),
            ]);
        } else {
            // Create user pending / default
            $familyId = DB::table('families')->value('id') ?? 'fam_utama';
            DB::table('allowed_emails')->insert([
                'email' => $email,
                'role' => 'member',
                'family_id' => $familyId,
                'status' => 'pending',
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Session::put('user_email', $email);

        return response()->json([
            'session' => ['user' => ['email' => $email]],
            'error' => null
        ]);
    }

    /**
     * Auth: Reset Password
     */
    public function resetPassword(Request $request)
    {
        return response()->json([
            'data' => true,
            'error' => null
        ]);
    }

    /**
     * Auth: Logout
     */
    public function logout()
    {
        Session::forget('user_email');
        return response()->json(['error' => null]);
    }

    /**
     * Auth: Get Current Session
     */
    public function getSession()
    {
        $email = Session::get('user_email');
        if (!$email) {
            return response()->json(['session' => null, 'error' => null]);
        }

        return response()->json([
            'session' => [
                'user' => ['email' => $email]
            ],
            'error' => null
        ]);
    }

    /**
     * Generic Supabase Query Engine Bridge
     */
    public function query(Request $request)
    {
        $table = $request->input('table');
        $action = $request->input('action', 'select'); // select, insert, update, delete, upsert
        $where = $request->input('where', []);
        $orders = $request->input('orders', []);
        $limit = $request->input('limit', null);
        $single = $request->input('single', false);
        $maybeSingle = $request->input('maybeSingle', false);
        $data = $request->input('data', []);
        $options = $request->input('options', []);

        if (empty($table)) {
            return response()->json(['data' => null, 'error' => ['message' => 'Table name is required.']]);
        }

        try {
            $query = DB::table($table);

            // Apply WHERE conditions
            foreach ($where as $w) {
                $field = $w['field'];
                $op = $w['op'] ?? '=';
                $value = $w['value'];

                if ($op === 'in') {
                    $query->whereIn($field, (array) $value);
                } else {
                    $query->where($field, $op, $value);
                }
            }

            // Apply ORDER BY
            foreach ($orders as $o) {
                $query->orderBy($o['field'], $o['dir'] ?? 'asc');
            }

            // Execute Actions
            if ($action === 'select') {
                if ($limit) {
                    $query->limit($limit);
                }
                $results = $query->get()->map(function ($row) {
                    return (array) $row;
                })->all();

                if ($single) {
                    $first = $results[0] ?? null;
                    if (!$first) {
                        return response()->json(['data' => null, 'error' => ['message' => 'No rows found.']]);
                    }
                    return response()->json(['data' => $first, 'error' => null]);
                }

                if ($maybeSingle) {
                    return response()->json(['data' => $results[0] ?? null, 'error' => null]);
                }

                return response()->json(['data' => $results, 'error' => null]);
            }

            if ($action === 'insert') {
                $rows = is_array($data) && isset($data[0]) && is_array($data[0]) ? $data : [$data];
                $inserted = [];

                foreach ($rows as $row) {
                    if (!isset($row['id']) && in_array($table, ['transactions', 'transfers', 'categories', 'tempat'])) {
                        $row['id'] = (string) Str::uuid();
                    }
                    if (!isset($row['created_at'])) {
                        $row['created_at'] = now();
                    }
                    if (!isset($row['updated_at'])) {
                        $row['updated_at'] = now();
                    }

                    DB::table($table)->insert($row);
                    $inserted[] = $row;
                }

                if ($single || $maybeSingle) {
                    return response()->json(['data' => $inserted[0] ?? null, 'error' => null]);
                }

                return response()->json(['data' => $inserted, 'error' => null]);
            }

            if ($action === 'update') {
                $data['updated_at'] = now();
                $affected = $query->update($data);
                return response()->json(['data' => $affected, 'error' => null]);
            }

            if ($action === 'delete') {
                $affected = $query->delete();
                return response()->json(['data' => $affected, 'error' => null]);
            }

            if ($action === 'upsert') {
                $rows = is_array($data) && isset($data[0]) && is_array($data[0]) ? $data : [$data];
                $onConflict = $options['onConflict'] ?? null;

                foreach ($rows as $row) {
                    $row['updated_at'] = now();
                    if (!isset($row['created_at'])) {
                        $row['created_at'] = now();
                    }

                    if ($onConflict) {
                        $conflictFields = explode(',', $onConflict);
                        $conflictWhere = [];
                        foreach ($conflictFields as $cf) {
                            $cf = trim($cf);
                            if (isset($row[$cf])) {
                                $conflictWhere[$cf] = $row[$cf];
                            }
                        }

                        if (!empty($conflictWhere)) {
                            $existing = DB::table($table)->where($conflictWhere)->first();
                            if ($existing) {
                                DB::table($table)->where($conflictWhere)->update($row);
                                continue;
                            }
                        }
                    }

                    DB::table($table)->insert($row);
                }

                return response()->json(['data' => $rows, 'error' => null]);
            }

            return response()->json(['data' => null, 'error' => ['message' => 'Unknown action.']]);
        } catch (\Exception $e) {
            return response()->json(['data' => null, 'error' => ['message' => $e->getMessage()]]);
        }
    }

    /**
     * Storage: Upload Avatar Photo
     */
    public function uploadPhoto(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['data' => null, 'error' => ['message' => 'No file uploaded.']]);
        }

        $file = $request->file('file');
        $filename = 'avatar_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $disk = config('filesystems.default');

        $path = $file->storeAs('avatars', $filename, $disk);
        $publicUrl = Storage::disk($disk)->url($path);

        return response()->json([
            'data' => ['publicUrl' => $publicUrl],
            'error' => null
        ]);
    }
}
