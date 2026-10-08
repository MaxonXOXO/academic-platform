<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add office workflow and historical fields to staff_leave_requests
        if (Schema::hasTable('staff_leave_requests')) {
            Schema::table('staff_leave_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('staff_leave_requests', 'office_status')) {
                    $table->string('office_status', 30)->default('Pending')->after('submitted_at');
                }
                if (!Schema::hasColumn('staff_leave_requests', 'office_mobile')) {
                    $table->string('office_mobile', 20)->nullable()->after('office_status');
                }
                if (!Schema::hasColumn('staff_leave_requests', 'office_name')) {
                    $table->string('office_name', 150)->nullable()->after('office_mobile');
                }
                if (!Schema::hasColumn('staff_leave_requests', 'office_remarks')) {
                    $table->text('office_remarks')->nullable()->after('office_name');
                }
                if (!Schema::hasColumn('staff_leave_requests', 'office_action_at')) {
                    $table->dateTime('office_action_at')->nullable()->after('office_remarks');
                }
                if (!Schema::hasColumn('staff_leave_requests', 'is_historical')) {
                    $table->boolean('is_historical')->default(false)->after('office_action_at');
                }
            });
        }

        // 2. Create sf_staff_ccl_credits table for tracking holiday/Saturday punch CCL
        if (!Schema::hasTable('sf_staff_ccl_credits')) {
            Schema::create('sf_staff_ccl_credits', function (Blueprint $table) {
                $table->id();
                $table->string('staff_mobile', 20);
                $table->string('staff_name', 150)->nullable();
                $table->date('duty_date');
                $table->string('session_type', 30)->default('Full Day'); // Full Day (1.0), Half Day (0.5)
                $table->decimal('earned_days', 4, 1)->default(1.0);
                $table->decimal('used_days', 4, 1)->default(0.0);
                $table->date('valid_until');
                $table->unsignedBigInteger('source_punch_id')->nullable();
                $table->string('status', 30)->default('Active'); // Active, Consumed, Expired
                $table->string('remarks', 255)->nullable();
                $table->string('credited_by', 100)->default('SF_Office');
                $table->timestamps();

                $table->index(['staff_mobile', 'status']);
                $table->index('duty_date');
            });
        }

        // 3. Initialize default system settings for SF Office leave rules
        $defaults = [
            'sf_office_leave_year_start' => '04-01',
            'sf_office_leave_year_end'   => '03-31',
            'sf_office_annual_cl_quota'  => '15',
            'sf_office_ccl_validity_days'=> '60',
        ];

        foreach ($defaults as $key => $val) {
            $exists = DB::table('system_settings')->where('key', $key)->exists();
            if (!$exists) {
                DB::table('system_settings')->insert([
                    'key'        => $key,
                    'value'      => $val,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 4. Seed user 9000000009 with password admin123
        $existingUser = DB::table('staff_profiles')->where('mobile_no', '9000000009')->first();
        if (!$existingUser) {
            DB::table('staff_profiles')->insert([
                'mobile_no'      => '9000000009',
                'name'           => 'SF Office Desk',
                'email'          => 'sfoffice@carmelpolytechnic.ac.in',
                'branch'         => 'GEN_SF',
                'designation'    => 'SF_Office',
                'dob'            => '1985-05-15',
                'password'       => Hash::make('admin123'),
                'account_status' => 'Approved',
                'photo_url'      => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sf_staff_ccl_credits');

        if (Schema::hasTable('staff_leave_requests')) {
            Schema::table('staff_leave_requests', function (Blueprint $table) {
                $columns = ['office_status', 'office_mobile', 'office_name', 'office_remarks', 'office_action_at', 'is_historical'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('staff_leave_requests', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
