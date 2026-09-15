<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Pairing;
use App\Models\Inquiry;
use App\Models\Profile;
use App\Models\CoupleTimelineDraft;
use App\Models\CoupleInvestmentPlannerDraft;
use Illuminate\Support\Facades\DB;
use Chat;

class DeleteCouples extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couple:delete {ids*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete one or more couples and all of their related data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->argument('ids') as $id) {
            $user = User::where('id', $id)->first();

            if (! $user) {
                $this->error("Couple {$id} not found, skipping.");
                continue;
            }

            try {
                $this->deleteCouple($user);
                $this->info("Couple {$id} and its relationships were deleted.");
            } catch (\Throwable $e) {
                $this->error("Couple {$id} could not be deleted, rolled back: " . $e->getMessage());
            }
        }
    }

    protected function deleteCouple(User $user): void
    {
        // Wrapped so a failure partway through (e.g. one delete throws)
        // rolls back everything for this couple instead of leaving orphaned
        // partial deletes — the whole thing is one all-or-nothing unit.
        DB::transaction(function () use ($user) {
            //delete conversations
            $convos = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
                ->setParticipant($user)
                ->page(1)
                ->get();
            foreach ($convos as $convo) {
                $convo->delete();
            }

            //remove favorites
            Favorite::where('user_id', $user->id)->delete();

            //remove pairings
            Pairing::where('client_id', $user->id)->delete();

            //remove inquiries
            Inquiry::where('user_id', $user->id)->delete();

            //delete meetings
            $user->meetings()->delete();

            //delete planning tool drafts
            CoupleTimelineDraft::where('user_id', $user->id)->delete();
            CoupleInvestmentPlannerDraft::where('user_id', $user->id)->delete();

            //delete profile
            Profile::where('type', 'client')->where('belongs_to', $user->id)->delete();

            //delete couple
            $user->delete();
        });
    }
}
