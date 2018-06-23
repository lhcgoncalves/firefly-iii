<?php
/**
 * RecurringRepositoryInterface.php
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

namespace FireflyIII\Repositories\Recurring;

use Carbon\Carbon;
use FireflyIII\Exceptions\FireflyException;
use FireflyIII\Models\Recurrence;
use FireflyIII\Models\RecurrenceRepetition;
use FireflyIII\Models\RecurrenceTransaction;
use FireflyIII\User;
use Illuminate\Support\Collection;


/**
 * Interface RecurringRepositoryInterface
 *
 * @package FireflyIII\Repositories\Recurring
 */
interface RecurringRepositoryInterface
{
    /**
     * Destroy a recurring transaction.
     *
     * @param Recurrence $recurrence
     */
    public function destroy(Recurrence $recurrence): void;

    /**
     * Returns all of the user's recurring transactions.
     *
     * @return Collection
     */
    public function get(): Collection;

    /**
     * Get ALL recurring transactions.
     *
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * Get the budget ID from a recurring transaction transaction.
     *
     * @param RecurrenceTransaction $recurrenceTransaction
     *
     * @return null|int
     */
    public function getBudget(RecurrenceTransaction $recurrenceTransaction): ?int;

    /**
     * Get the category from a recurring transaction transaction.
     *
     * @param RecurrenceTransaction $recurrenceTransaction
     *
     * @return null|string
     */
    public function getCategory(RecurrenceTransaction $recurrenceTransaction): ?string;

    /**
     * Get the notes.
     *
     * @param Recurrence $recurrence
     *
     * @return string
     */
    public function getNoteText(Recurrence $recurrence): string;

    /**
     * Generate events in the date range.
     *
     * @param RecurrenceRepetition $repetition
     * @param Carbon               $start
     * @param Carbon               $end
     *
     * @throws FireflyException
     *
     * @return array
     */
    public function getOccurrencesInRange(RecurrenceRepetition $repetition, Carbon $start, Carbon $end): array;

    /**
     * Get the tags from the recurring transaction.
     *
     * @param Recurrence $recurrence
     *
     * @return array
     */
    public function getTags(Recurrence $recurrence): array;

    /**
     * Calculate the next X iterations starting on the date given in $date.
     * Returns an array of Carbon objects.
     *
     * @param RecurrenceRepetition $repetition
     * @param Carbon               $date
     * @param int                  $count
     *
     * @throws FireflyException
     * @return array
     */
    public function getXOccurrences(RecurrenceRepetition $repetition, Carbon $date, int $count): array;

    /**
     * Parse the repetition in a string that is user readable.
     *
     * @param RecurrenceRepetition $repetition
     *
     * @return string
     */
    public function repetitionDescription(RecurrenceRepetition $repetition): string;

    /**
     * Set user for in repository.
     *
     * @param User $user
     */
    public function setUser(User $user): void;

    /**
     * Store a new recurring transaction.
     *\
     *
     * @param array $data
     *
     * @throws FireflyException
     * @return Recurrence
     */
    public function store(array $data): Recurrence;

    /**
     * Update a recurring transaction.
     *
     * @param Recurrence $recurrence
     * @param array      $data
     *
     * @return Recurrence
     */
    public function update(Recurrence $recurrence, array $data): Recurrence;

}