<?php

namespace App\Test\Controller;

use App\Entity\Rent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/rent/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Rent::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Rent index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'rent[rental_start_date]' => 'Testing',
            'rent[rental_end_date]' => 'Testing',
            'rent[user]' => 'Testing',
            'rent[product]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Rent();
        $fixture->setRental_start_date('My Title');
        $fixture->setRental_end_date('My Title');
        $fixture->setUser('My Title');
        $fixture->setProduct('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Rent');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Rent();
        $fixture->setRental_start_date('Value');
        $fixture->setRental_end_date('Value');
        $fixture->setUser('Value');
        $fixture->setProduct('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'rent[rental_start_date]' => 'Something New',
            'rent[rental_end_date]' => 'Something New',
            'rent[user]' => 'Something New',
            'rent[product]' => 'Something New',
        ]);

        self::assertResponseRedirects('/rent/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getRental_start_date());
        self::assertSame('Something New', $fixture[0]->getRental_end_date());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getProduct());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Rent();
        $fixture->setRental_start_date('Value');
        $fixture->setRental_end_date('Value');
        $fixture->setUser('Value');
        $fixture->setProduct('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/rent/');
        self::assertSame(0, $this->repository->count([]));
    }
}
