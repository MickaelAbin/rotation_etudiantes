<?php

namespace App\Service;
use App\Entity\Student;
use App\Entity\UniversityCalendar;
use App\Repository\PublicHolidayRepository;
use App\Repository\ClinicalRotationCategoriesRepository;
use App\Repository\NoRotationPeriodRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;

class GuardScheduler
{




    public function __construct(EntityManagerInterface $entityManager,
                                PublicHolidayRepository $publicHolidayRepository,
                                NoRotationPeriodRepository $noRotationPeriodRepository,
                                StudentRepository $studentRepository,
                                ClinicalRotationCategoriesRepository  $clinicalRotationCategoriesRepository)
    {
        $this->entityManager = $entityManager;
        $this->PublicHolidayRepository = $publicHolidayRepository;
        $this->NoRotationPeriodRepository = $noRotationPeriodRepository;
        $this->StudentRepository = $studentRepository;
        $this->ClinicalRotationCategoriesRepository = $clinicalRotationCategoriesRepository;
    }

    public function createAvailableDaysArray($academicLevel)
    {
//        // Récupération de la liste des jours fériés
//        $holidays = $this->PublicHolidayRepository->findAll();
//        $holidaysDates = [];
//        foreach ($holidays as $holiday) {
//            $holidaysDates[] = $holiday->getDate();
//        }

        // Récupération de la plage de dates correspondant à l'academic level spécifié
        $universityCalendar = $this->entityManager->getRepository(UniversityCalendar::class)->findOneBy(['academicLevel' => $academicLevel]);

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
        $students = $this->StudentRepository->createQueryBuilder('s')
            ->where('s.academicLevel = :academicLevel')
            ->setParameter('academicLevel', $academicLevelId)
            ->getQuery()
            ->getResult();

        // Mélange de la liste d'utilisateurs
        shuffle($students);

        return $students;
    }

    public function categorybyid(int $academicLevelId): array
    {
        // Récupération des categories de l'academic level spécifié
        $clinicalRotationCategories = $this->ClinicalRotationCategoriesRepository->createQueryBuilder('c')
            ->where('c.academicLevel = :academicLevel')
            ->setParameter('academicLevel', $academicLevelId)
            ->getQuery()
            ->getResult();

        // Mélange de la liste d'utilisateurs
        shuffle($clinicalRotationCategories);

        return $clinicalRotationCategories;
    }
}