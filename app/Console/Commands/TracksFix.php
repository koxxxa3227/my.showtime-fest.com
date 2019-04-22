<?php

namespace App\Console\Commands;

use App\Models\Track;
use function foo\func;
use Illuminate\Console\Command;

class TracksFix extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $tracks = Track::with( 'application.category' )->get();
        foreach ( $tracks as $track ) {
            $path = public_path('uploads/' . $track->path);
            rescue( function () use ( $track ) {
                $category = $track->application->category;
                $filename = $track->application_id . '_' . $track->title;

                \File::move( public_path( 'uploads/' . $track->path ), public_path( 'uploads/tracks/' . $category->title . '/' . $filename ) );

                $track->path  = "tracks/$category->title/$filename";
                $track->title = $filename;

                $track->save();
                $this->line( 'Saved' );
            }, function () use ( $path ) {
                $this->line( 'The track not found in path ' . $path );
            } );
        }
    }
}
