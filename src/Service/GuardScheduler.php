<?php

namespace App\Service;
use App\Entity\User;
use App\Repository\PublicHolidayRepository;
use App\Repository\NoRotationPeriodRepository;
use App\Repository\StudentRepository;
use App\Repository\UserRepository;

class GuardScheduler
{




    public function __construct(PublicHolidayRepository $publicHolidayRepository, NoRotationPeriodRepository $noRotationPeriodRepository, StudentRepository $studentRepository)
    {
        $this->PublicHolidayRepository = $publicHolidayRepository;
        $this->NoRotationPeriodRepository = $noRotationPeriodRepository;
        $this->StudentRepository = $studentRepository;
    }

    public function createAvailableDaysArray($universityCalendar)
    {
//        // Récupération de la liste des jours fériés
//        $holidays = $this->PublicHolidayRepository->findAll();
//        $holidaysDates = [];
//        foreach ($holidays as $holiday) {
//            $holidaysDates[] = $holiday->getDate();
//        }

        // Récupération des périodes sans garde
        $noRotationPeriodRepository = $this->NoRotationPeriodRepository->findAll();

        // Création d'un tableau de dates avec tous les jours de la promotion
        $startDate = new \DateTime($universityCalendar->getStartDate()->format('Y-m-d'));
        $endDate = new \DateTime($universityCalendar->getEndDate()->format('Y-m-d'));
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);
        $availableDays = [];
        foreach ($dateRange as $date) {
            // Vérification que le jour n'est pas une période sans garde
            $isGuardedDay = true;
            foreach ($noRotationPeriodRepository as $period) {
                if ($date >= $period->getStartDate() && $date <= $period->getEndDate()) {
                    $isGuardedDay = false;
                    break;
                }
            }
            if ($isGuardedDay) {
                $availableDays[] = $date;
            }
        }
        return $availableDays;
    }

    public function shuffleUsersByAcademicLevel(int $academicLevelId): array
    {
        // Récupération des utilisateurs de l'academic level spécifié
        $studentRepository = $this->StudentRepository->getRepository(User::class);
        $students = $studentRepository->findBy(['academicLevel' => $academicLevelId]);

        // Mélange de la liste d'utilisateurs
        shuffle($students);

        return $students;
    }
}