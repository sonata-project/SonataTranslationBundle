<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

final class GedmoCRUDTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
    }

    /**
     * @dataProvider provideListCases
     */
    public function testList(string $locale, string $name): void
    {
        $url = $this->generateUrlWithLocale('/admin/tests/app/gedmocategory/list', $locale);

        $this->client->request(Request::METHOD_GET, $url);

        self::assertSelectorTextContains('.sonata-ba-list-field-string[objectid="category_novel"] .sonata-link-identifier', $name);
    }

    /**
     * @return iterable<array{string, string}>
     */
    public function provideListCases(): iterable
    {
        yield 'default english' => ['', 'Novel'];
        yield 'english' => ['en', 'Novel'];
        yield 'spanish' => ['es', 'Novela'];
        yield 'french' => ['fr', 'Roman'];
    }

    /**
     * @dataProvider provideCreateCases
     */
    public function testCreate(string $locale, string $newName): void
    {
        $url = $this->generateUrlWithLocale('/admin/tests/app/gedmocategory/create', $locale);

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $attributeId = (string) $crawler->filter('.category_id')->attr('name');
        $attributeName = (string) $crawler->filter('.category_name')->attr('name');

        $newId = 'new_ '.$newName;

        $this->client->submitForm('Create and return to list', [
            $attributeId => $newId,
            $attributeName => $newName,
        ]);

        self::assertSelectorTextContains(
            '.alert-success',
            sprintf('"%s" has been successfully created.', $newName)
        );

        $url = $this->generateUrlWithLocale('/admin/tests/app/gedmocategory/list', $locale);

        $this->client->request(Request::METHOD_GET, $url);

        self::assertSelectorTextContains(
            sprintf('.sonata-ba-list-field-string[objectid="%s"] .sonata-link-identifier', $newId),
            $newName
        );
    }

    /**
     * @return iterable<array{string, string}>
     */
    public function provideCreateCases(): iterable
    {
        yield 'default english' => ['', 'Default New Novel'];
        yield 'english' => ['en', 'New Novel'];
        yield 'spanish' => ['es', 'Nueva Novela'];
        yield 'french' => ['fr', 'Nouveau Roman'];
    }

    /**
     * @dataProvider provideEditCases
     */
    public function testEdit(string $locale, string $editedName): void
    {
        $url = $this->generateUrlWithLocale('/admin/tests/app/gedmocategory/category_novel/edit', $locale);

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $attributeName = (string) $crawler->filter('.category_name')->attr('name');

        $this->client->submitForm('Update and close', [
            $attributeName => $editedName,
        ]);

        self::assertSelectorTextContains(
            '.alert-success',
            sprintf('"%s" has been successfully updated.', $editedName)
        );

        $url = $this->generateUrlWithLocale('/admin/tests/app/gedmocategory/list', $locale);

        $this->client->request(Request::METHOD_GET, $url);

        self::assertSelectorTextContains(
            '.sonata-ba-list-field-string[objectid="category_novel"] .sonata-link-identifier',
            $editedName
        );
    }

    /**
     * @return iterable<array{string, string}>
     */
    public function provideEditCases(): iterable
    {
        yield 'default english' => ['', 'Edited Default Novel'];
        yield 'english' => ['en', 'Edited Novel'];
        yield 'spanish' => ['es', 'Novela editada'];
        yield 'french' => ['fr', 'Roman édité'];
    }

    private function generateUrlWithLocale(string $url, string $locale): string
    {
        if ('' === $locale) {
            return $url;
        }

        return $url.'?tl='.$locale;
    }
}
