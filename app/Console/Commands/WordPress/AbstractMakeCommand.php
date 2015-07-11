<?php

namespace App\Console\Commands\WordPress;

use Illuminate\Console\Command;

abstract class AbstractMakeCommand extends Command
{
    /**
     * @var array
     */
    protected $skeletons = [];

    /**
     * @var string
     */
    protected $default_skeleton;

    /**
     * Choose skeleton by command line parameter or dialog.
     *
     * @param string $skeleton
     * @return string
     */
    protected function chooseSkeleton($skeleton)
    {
        if ($skeleton) {
            if (!in_array($skeleton, $this->skeletons)) {
                throw new \InvalidArgumentException("Skeleton '$skeleton' is not found.");
            }
        } else {
            $skeleton = $this->choice('Skeleton type', $this->skeletons, array_search($this->default_skeleton, $this->skeletons));
        }

        return $skeleton;
    }
}
