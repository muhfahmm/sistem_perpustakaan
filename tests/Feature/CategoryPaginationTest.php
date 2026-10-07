<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class CategoryPaginationTest extends TestCase
{
    public function test_last_page_uses_bootstrap_pagination_and_disables_next_link(): void
    {
        $paginator = new LengthAwarePaginator(
            collect(range(11, 15)),
            15,
            10,
            2,
            ['path' => '/admin-panel/categories']
        );

        $html = $paginator->onEachSide(1)->links('pagination::bootstrap-5')->render();

        $this->assertStringContainsString('class="page-link"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
        $this->assertMatchesRegularExpression(
            '/<li class="page-item disabled"[^>]*aria-label="Next[^"]*"/',
            $html
        );
        $this->assertStringNotContainsString('rel="next"', $html);
    }
}
