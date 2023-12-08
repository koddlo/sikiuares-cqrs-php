<?php

declare(strict_types=1);

namespace Koddlo\Cqrs\Booking\Infrastructure\Symfony\Cli;

use Doctrine\ORM\EntityManagerInterface;
use Koddlo\Cqrs\Booking\Domain\Date;
use Koddlo\Cqrs\Booking\Domain\Duration;
use Koddlo\Cqrs\Booking\Domain\StafferId;
use Koddlo\Cqrs\Booking\Domain\Time;
use Koddlo\Cqrs\Booking\Domain\WorkingDay;
use Koddlo\Cqrs\Booking\Domain\WorkingDayId;
use Koddlo\Cqrs\Booking\Domain\WorkingDayRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'koddlo:booking:create-sample-working-days',
    description: 'Create sample working days.'
)]
final class CreateSampleWorkingDays extends Command
{
    public function __construct(
        private WorkingDayRepository $workingDayRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $workingDays[] = WorkingDay::create(
            new WorkingDayId('a21c5b8a-1685-72fb-b8a4-21b18618cb4d'),
            new StafferId('018c25b8-1cdb-72c7-ab50-2ec8a263d27b'),
            Date::fromString('2023-11-30'),
            [
                Duration::create(Time::fromString('08:00'), Time::fromString('16:00')),
            ]
        );

        $workingDays[] = WorkingDay::create(
            new WorkingDayId('231b25b8-1685-72fb-b8a4-21b18618cb4d'),
            new StafferId('018c25b8-1cdb-72c7-ab50-2ec8a263d27b'),
            Date::fromString('2023-12-01'),
            [
                Duration::create(Time::fromString('09:00'), Time::fromString('10:30')),
                Duration::create(Time::fromString('12:30'), Time::fromString('20:00')),
            ]
        );

        $workingDays[] = WorkingDay::create(
            new WorkingDayId('018c25b8-1685-72fb-b8a4-21b18618cb4d'),
            new StafferId('3abc25b8-1cdb-72c7-ab50-2ec8a263d27b'),
            Date::fromString('2023-12-01'),
            [
                Duration::create(Time::fromString('08:00'), Time::fromString('11:00')),
                Duration::create(Time::fromString('12:00'), Time::fromString('17:00')),
            ]
        );

        foreach ($workingDays as $workingDay) {
            $this->workingDayRepository->save($workingDay);
        }

        $this->entityManager->flush();

        $io->success('Three sample working days have been created.');

        return Command::SUCCESS;
    }
}
