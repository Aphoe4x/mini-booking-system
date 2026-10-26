<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::active()
            ->withCount([
                'companyServices as available_services' => fn ($q) => $q->where('is_available', true),
            ])
            ->withMin([
                'companyServices as min_price' => fn ($q) => $q->where('is_available', true),
            ], 'price')
            ->whereHas('companyServices', function ($q) {
                $q->where('is_available', true);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $companies = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('companies.index', compact('companies'));
    }

    public function show(Company $company)
    {
        abort_unless($company->is_active, 404);

        $company->load(['services' => function ($query) {
            $query->wherePivot('is_available', true);
        }, 'companyServices' => function ($query) {
            $query->where('is_available', true)->with('service');
        }]);

        $availableServicesCount = $company->companyServices->where('is_available', true)->count();
        $categories = $company->services->groupBy('category');

        return view('companies.show', compact('company', 'categories', 'availableServicesCount'));
    }
}
