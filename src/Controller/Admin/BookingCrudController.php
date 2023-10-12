<?php

namespace App\Controller\Admin;

use DateTime;
use Exception;
use App\Entity\Booking;
use App\Service\BookingService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BookingCrudController extends AbstractCrudController
{

    private $bookingService;
    private $entityManager;

    public function __construct(BookingService $bookingService, EntityManagerInterface $entityManager)
    {
        $this->bookingService = $bookingService;
        $this->entityManager = $entityManager;
    }
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }
    public function createEntity(string $entityFqcn)
    {
        $booking = new Booking(new DateTime(),new DateTime(),null);
        return $booking;

    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $bookingResponse = $this->bookingService->createBooking(
            $entityInstance->getRoom(),
            $entityInstance->getStartDate(),
            $entityInstance->getEndDate()
        );

        if (!$bookingResponse->isSuccess()) {
            $this->addFlash('error', $bookingResponse->getMessage());
        }
        else{
            $this->addFlash('success', $bookingResponse->getMessage());
        }




    }
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield DateTimeField::new('startDate')->setColumns(8);
        yield DateTimeField::new('endDate')->setColumns(8);
        yield AssociationField::new('room')->setColumns(8);
    }
    
}
