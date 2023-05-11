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

    public function createAvailableDaysArray(int $academicLevel)
    {
        // Récupération de la plage de dates correspondant à l'academic level spécifié
        $universityCalendar = $this->entityManager->getRepository(UniversityCalendar::class)->findOneBy(['academicLevel' => $academicLevel]);

        // Récupération des périodes sans garde
        $noRotationPeriods = $universityCalendar->getNoRotationPeriods();

        // Création d'un tableau de dates avec tous les jours de la promotion
        $startDate = new \DateTime($universityCalendar->getStartDate()->format('Y-m-d'));
        $endDate = new \DateTime($universityCalendar->getEndDate()->format('Y-m-d'));
        $interval = new \DateInterval('P1D');
        $dateRange = new \DatePeriod($startDate, $interval, $endDate);
        $availableDays = [];
        foreach ($dateRange as $date) {
            // Vérification que le jour n'est pas une période sans garde
            $isGuardedDay = true;
            foreach ($noRotationPeriods as $period) {
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
            ->leftJoin('s.enrolments', 'e')
            ->addSelect('COUNT(e) AS HIDDEN enrolment_count')
            ->where('s.academicLevel = :academicLevel')
            ->setParameter('academicLevel', $academicLevelId)
            ->groupBy('s.moodleUserID')
            ->orderBy('enrolment_count', 'DESC')
            ->getQuery()
            ->getResult();

        // Grouper les étudiants par nombre d'enrolments
        $groupedStudents = [];
        foreach ($students as $student) {
            $enrolmentCount = $student->getEnrolments()->count();
            if (!isset($groupedStudents[$enrolmentCount])) {
                $groupedStudents[$enrolmentCount] = [];
            }
            $groupedStudents[$enrolmentCount][] = $student;
        }

        // Mélanger les groupes d'étudiants et les concaténer
        $shuffledStudents = [];
        foreach ($groupedStudents as $group) {
            shuffle($group);
            $shuffledStudents = array_merge($shuffledStudents, $group);
        }

        return $shuffledStudents;
    }

    public function categorybyid(int $academicLevelId): array
    {
        // Récupération des categories de l'academic level spécifié
        $clinicalRotationCategories = $this->ClinicalRotationCategoriesRepository->createQueryBuilder('c')
            ->where('c.academicLevel = :academicLevel')
            ->setParameter('academicLevel', $academicLevelId)
            ->getQuery()
            ->getResult();

        return $clinicalRotationCategories;
    }
    public function createAvailableDaysHolidaysArray(int $academicLevel)
    {
        $holidays = array();
        // Récupération de la plage de dates correspondant à l'academic level spécifié
        $universityCalendar = $this->entityManager->getRepository(UniversityCalendar::class)->findOneBy(['academicLevel' => $academicLevel]);

        // Récupération des périodes sans garde
        $holidaysDates = $universityCalendar->getPublicHolidays();
        foreach ($holidaysDates as $publicHoliday) {
            $holidays[] = $publicHoliday->getDate();
        }
        return $holidays;
    }
}