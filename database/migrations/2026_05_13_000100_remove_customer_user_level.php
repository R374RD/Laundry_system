<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'customer')
            ->update([
                'role' => 'staff',
                'is_active' => false,
                'branch_id' => null,
            ]);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff'");
        }

        if (DB::connection()->getDriverName() === 'sqlite') {
            $this->replaceSqliteRoleCheck(
                "check (\"role\" in ('admin', 'staff', 'customer'))",
                "check (\"role\" in ('admin', 'staff'))"
            );
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'staff'");
        }

        if (DB::connection()->getDriverName() === 'sqlite') {
            $this->replaceSqliteRoleCheck(
                "check (\"role\" in ('admin', 'staff'))",
                "check (\"role\" in ('admin', 'staff', 'customer'))"
            );
        }
    }

    private function replaceSqliteRoleCheck(string $from, string $to): void
    {
        $row = DB::selectOne("select sql from sqlite_master where type = 'table' and name = 'users'");

        if (! $row || ! str_contains($row->sql, $from)) {
            return;
        }

        DB::statement('PRAGMA writable_schema = ON');
        DB::update("update sqlite_master set sql = ? where type = 'table' and name = 'users'", [
            str_replace($from, $to, $row->sql),
        ]);
        DB::statement('PRAGMA writable_schema = OFF');
    }
};
