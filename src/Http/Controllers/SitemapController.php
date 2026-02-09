<?php

namespace IbrahimEng12\Seoquent\Http\Controllers;

use IbrahimEng12\Seoquent\Facades\Seo;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $xml = Seo::sitemap()->generate();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
