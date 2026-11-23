<?php

declare(strict_types=1);

namespace Yireo\AdminReactComponents\Test\Unit\Controller\Adminhtml\Ajax;

use Magento\Framework\App\Request\Http;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Yireo\AdminReactComponents\Controller\Adminhtml\Ajax\ProductSearch;

class AbstractSearchTest extends TestCase
{
    /**
     * @dataProvider getSearchWordsProvider
     */
    public function testGetSearchWords(string $search, array $expectedWords): void
    {
        $this->assertSame($expectedWords, $this->invoke('getSearchWords', ['search' => $search]));
    }

    public static function getSearchWordsProvider(): array
    {
        return [
            ['', []],
            ['   ', []],
            ['foo', ['foo']],
            ["  foo \t bar\nfoo ", ['foo', 'bar']],
            ['a b c d e f g', ['a', 'b', 'c', 'd', 'e']],
            [str_repeat('x', 150), [str_repeat('x', 100)]],
        ];
    }

    public function testEscapeLikeValue(): void
    {
        $this->assertSame('a\\%b\\_c\\\\d', $this->invoke('escapeLikeValue', [], ['a%b_c\\d']));
    }

    /**
     * @dataProvider getSortProvider
     */
    public function testSortFieldAndDirection(
        string $sortField,
        string $sortDirection,
        string $expectedField,
        string $expectedDirection
    ): void {
        $params = ['sortField' => $sortField, 'sortDirection' => $sortDirection];
        $this->assertSame($expectedField, $this->invoke('getSortField', $params));
        $this->assertSame($expectedDirection, $this->invoke('getSortDirection', $params));
    }

    public static function getSortProvider(): array
    {
        return [
            ['id', 'desc', 'entity_id', 'DESC'],
            ['sku', 'ASC', 'sku', 'ASC'],
            ['password_hash', 'foobar', '', 'ASC'],
            ['name;DROP', '', '', 'ASC'],
        ];
    }

    private function invoke(string $method, array $params, array $arguments = []): mixed
    {
        $request = $this->createMock(Http::class);
        $request->method('getParam')->willReturnCallback(fn (string $name) => $params[$name] ?? null);

        $reflection = new ReflectionClass(ProductSearch::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $property = $reflection->getProperty('request');
        $property->setAccessible(true);
        $property->setValue($controller, $request);

        $reflectionMethod = $reflection->getMethod($method);
        $reflectionMethod->setAccessible(true);
        return $reflectionMethod->invokeArgs($controller, $arguments);
    }
}
