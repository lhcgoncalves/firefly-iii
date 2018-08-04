<?php
/**
 * CallbackController.php
 * Copyright (c) 2018 thegrumpydictator@gmail.com
 *
 * This file is part of Firefly III.
 *
 * Firefly III is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Firefly III is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Firefly III. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace FireflyIII\Http\Controllers\Import;


use FireflyIII\Http\Controllers\Controller;
use FireflyIII\Repositories\ImportJob\ImportJobRepositoryInterface;
use Illuminate\Http\Request;
use Log;

/**
 * Class CallbackController
 */
class CallbackController extends Controller
{

    /**
     * @param Request                      $request
     *
     * @param ImportJobRepositoryInterface $repository
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function ynab(Request $request, ImportJobRepositoryInterface $repository)
    {
        $code      = (string)$request->get('code');
        $jobKey    = (string)$request->get('state');
        $importJob = $repository->findByKey($jobKey);
        if ('' === $code) {
            return view('error')->with('message', 'You Need A Budget did not reply with a valid authorization code. Firefly III cannot continue.');
        }
        if ('' === $jobKey || null === $importJob) {
            return view('error')->with('message', 'You Need A Budget did not reply with the correct state identifier. Firefly III cannot continue.');
        }
        Log::debug(sprintf('Got a code from YNAB: %s', $code));

        // we have a code. Make the job ready for the next step, and then redirect the user.
        $configuration              = $repository->getConfiguration($importJob);
        $configuration['auth_code'] = $code;
        $repository->setConfiguration($importJob, $configuration);

        // set stage to make the import routine take the correct action:
        $repository->setStatus($importJob, 'ready_to_run');
        $repository->setStage($importJob, 'get_access_token');

        return redirect(route('import.job.status.index', [$importJob->key]));
    }

}