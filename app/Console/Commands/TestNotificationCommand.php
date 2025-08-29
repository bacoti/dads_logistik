<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Project;
use App\Models\SubProject;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class TestNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test {--user= : User ID to create transaction for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test notification system by creating sample notifications';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Notification System...');

        // Get or create a test user
        $userId = $this->option('user');
        $user = $userId ? User::find($userId) : User::where('role', 'user')->first();

        if (!$user) {
            $this->error('No user found. Please create a user first or specify a user ID.');
            return 1;
        }

        // Get project and subproject for transaction
        $project = Project::first();
        $subProject = SubProject::first();

        if (!$project || !$subProject) {
            $this->error('No project or subproject found. Please create master data first.');
            return 1;
        }

        // Create a sample transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'type' => 'penerimaan',
            'transaction_date' => now(),
            'project_id' => $project->id,
            'sub_project_id' => $subProject->id,
            'location' => 'Test Location',
            'notes' => 'Test transaction for notification system',
        ]);

        $this->info("Created transaction ID: {$transaction->id}");

        // Test TransactionCreated notification
        $this->info('Sending TransactionCreated notification to admins...');
        $this->notificationService->notifyTransactionCreated($transaction);

        // Count admins who will receive notification
        $adminCount = User::where('role', 'admin')->count();
        $this->info("Notification sent to {$adminCount} admin(s).");

        // Test notification statistics
        $this->info('Getting notification statistics...');
        $stats = $this->notificationService->getNotificationStats();
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Users', $stats['total_users']],
                ['Admin Users', $stats['admin_count']],
                ['Field Users', $stats['field_user_count']],
                ['PO Users', $stats['po_user_count']],
            ]
        );

        $this->info('✅ Notification test completed successfully!');
        $this->info('You can now check the notifications in the web interface.');

        return 0;
    }
}
