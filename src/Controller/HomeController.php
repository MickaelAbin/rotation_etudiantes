<?php

namespace App\Controller;


use App\Entity\AcademicLevel;
use App\Entity\Admin;
use App\Entity\Student;
use App\Repository\AcademicLevelRepository;
use App\Repository\EnrolmentRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private array $academicLevelList;

    public function __construct(AcademicLevelRepository $academicLevelRepository)
    {
        $this->academicLevelList = $academicLevelRepository->findAll();
    }

    /**
     * @Route(path = "/", name = "home")
     */
    public function home(): Response
    {
        $user = $this->getUser();

        if ($user instanceof Admin)
        {
            return $this->render('home/home_admin.html.twig');
        }

        if ($user instanceof Student)
        {
            return $this->render('home/home_student.html.twig');
        }

        return $this->render('home/home.html.twig');
    }


    /**
     * @Route(path = "/calendar/{id<[2,3,4,5,6]>}", defaults = {"id": 2}, name = "calendar", methods = {"GET"})
     */
    public function calendar(EnrolmentRepository $enrolmentRepository, AcademicLevel $academicLevel = null): Response
    {
        $academicLevelList = $this->academicLevelList;
        $enrolments = $enrolmentRepository->findEnrolmentsByAcademicLevel($academicLevel->getId());

        $fullCalendarData = [];
        foreach ($enrolments as $enrolment) {
            $fullCalendarData[] = [
                'id' => $enrolment->getId(),
                'date' => $enrolment->getDate()->format('Y-m-d'),
                'title' => $enrolment->getClinicalRotationCategory()->getStartTime()->format('H') . 'h ' . $enrolment->getStudent(),
                'backgroundColor' => $enrolment->getClinicalRotationCategory()->getColor(),
                'extendedProps' => [
                    'student' => (string) $enrolment->getStudent(),
                    'academicLevel' => $enrolment->getStudent()->getAcademicLevel()->getLabel(),
                    'clinicalRotationCategory' => $enrolment->getClinicalRotationCategory()->getLabel(),
                    'startTime' => $enrolment->getClinicalRotationCategory()->getStartTime()->format('H'),
                    'endTime' => $enrolment->getClinicalRotationCategory()->getEndTime()->format('H'),
                ]
            ];
        }

        $data = json_encode($fullCalendarData);

        return $this->render('home/calendar.html.twig', compact('data','academicLevel', 'academicLevelList', 'enrolments'));
    }

    /**
     * @Route(path = "/enrolments-by-students/{id<[2,3,4,5,6]>}", defaults = {"id": 2}, name = "enrolments_by_students", methods = {"GET"})
     */
    public function enrolmentsByStudents(StudentRepository $studentRepository, AcademicLevel $academicLevel = null): Response
    {
        $students = $studentRepository->getStudentsWithEnrolmentsByAcademicLevel($academicLevel->getId());

        return $this->render('home/enrolments_by_students.html.twig', [
            'students' => $students,
            'academicLevel' => $academicLevel,
            'academicLevelList' => $this->academicLevelList,
            'path' => 'enrolments_by_students',
        ]);
    }





}
