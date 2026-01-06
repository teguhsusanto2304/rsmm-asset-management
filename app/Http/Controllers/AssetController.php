<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Department;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::with(['category', 'department', 'location', 'assignedUser'])
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('barcode', 'like', "%{$search}%")
                      ->orWhere('asset_tag', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->when($request->department_id, function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })
            ->when($request->location_id, function ($q) use ($request) {
                $q->where('location_id', $request->location_id);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->condition, function ($q) use ($request) {
                $q->where('condition', $request->condition);
            })
            ->when($request->assigned_to, function ($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $categories = $this->getHierarchicalCategories();
        $departments = $this->getHierarchicalDepartments();
        $locations = $this->getHierarchicalLocations();
        $users = User::all();

        return view('admin.assets.index', compact('assets', 'categories', 'departments', 'locations', 'users'));
    }

    public function create()
    {
        $categories = $this->getHierarchicalCategories();
        $departments = $this->getHierarchicalDepartments();
        $locations = $this->getHierarchicalLocations();
        $users = User::all();

        return view('admin.assets.create', compact(
            'categories',
            'departments',
            'locations',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if (empty($data['barcode'])) {
            $data['barcode'] = Str::upper(Str::random(10));
        }

        $data['created_by'] = auth()->id();

        $asset = Asset::create($data);

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset berhasil dibuat');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'department', 'location', 'assignedUser', 'creator', 'updater']);

        $qrSvg = null;
        if (class_exists(QrCode::class)) {
            $qrSvg = QrCode::format('svg')
                ->size(200)
                ->margin(1)
                ->generate($asset->barcode);
        }

        return view('admin.assets.show', compact('asset', 'qrSvg'));
    }

    public function label(Asset $asset)
    {
        $qrSvg = null;
        if (class_exists(QrCode::class)) {
            $qrSvg = QrCode::format('svg')
                ->size(280)
                ->margin(0)
                ->generate($asset->barcode);
        }

        return view('admin.assets.label', compact('asset', 'qrSvg'));
    }

    public function edit(Asset $asset)
    {
        $categories = $this->getHierarchicalCategories();
        $departments = $this->getHierarchicalDepartments();
        $locations = $this->getHierarchicalLocations();
        $users = User::all();

        return view('admin.assets.edit', compact(
            'asset',
            'categories',
            'departments',
            'locations',
            'users'
        ));
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $this->validateData($request, $asset->id);

        $data['updated_by'] = auth()->id();

        $asset->update($data);

        return redirect()
            ->route('assets.show', $asset)
            ->with('success', 'Asset berhasil diperbarui');
    }

    protected function validateData(Request $request, ?string $assetId = null): array
    {
        $barcodeRule = 'nullable|string|max:191|unique:asset,barcode';
        $assetTagRule = 'nullable|string|max:191|unique:asset,asset_tag';
        $serialRule = 'required|string|max:191|unique:asset,serial_number';

        if ($assetId) {
            $barcodeRule .= ',' . $assetId . ',id';
            $assetTagRule .= ',' . $assetId . ',id';
            $serialRule .= ',' . $assetId . ',id';
        }

        return $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => $barcodeRule,
            'asset_tag' => $assetTagRule,
            'serial_number' => $serialRule,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'department_id' => 'nullable|exists:departments,id',
            'location_id' => 'nullable|exists:locations,id',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'model_number' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:users,id',
            'assigned_date' => 'nullable|date',
            'return_date' => 'nullable|date|after_or_equal:assigned_date',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'purchase_order_number' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'supplier_contact' => 'nullable|string|max:255',
            'warranty_start_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:warranty_start_date',
            'warranty_terms' => 'nullable|string',
            'condition' => 'required|in:excellent,good,fair,poor,critical',
            'condition_notes' => 'nullable|string',
            'status' => 'required|in:available,assigned,maintenance,disposed,reserved',
            'installation_date' => 'nullable|date',
            'disposal_date' => 'nullable|date',
            'disposal_reason' => 'nullable|string',
            'depreciation_rate' => 'nullable|numeric',
            'depreciation_method' => 'nullable|in:straight_line,declining_balance,units_of_production',
            'useful_life_years' => 'nullable|integer',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'insurance_value' => 'nullable|numeric',
            'insurance_expiry_date' => 'nullable|date',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'maintenance_interval_days' => 'nullable|integer',
            'maintenance_notes' => 'nullable|string',
            'specifications' => 'nullable|array',
            'image' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);
    }

    /**
     * Get hierarchical categories as flat array with indentation
     */
    protected function getHierarchicalCategories(): array
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->with('children');
            }])
            ->get();
        
        return $this->buildHierarchicalList($categories, 'name', 'children');
    }

    /**
     * Get hierarchical departments as flat array with indentation
     */
    protected function getHierarchicalDepartments(): array
    {
        $departments = Department::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->with('children');
            }])
            ->get();
        
        return $this->buildHierarchicalList($departments, 'department', 'children');
    }

    /**
     * Get hierarchical locations as flat array with indentation
     */
    protected function getHierarchicalLocations(): array
    {
        $locations = Location::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->with('children');
            }])
            ->get();
        
        return $this->buildHierarchicalList($locations, 'name', 'children');
    }

    /**
     * Build a flat hierarchical list with indentation
     */
    protected function buildHierarchicalList($items, string $nameField, string $childrenRelation, int $level = 0): array
    {
        $result = [];
        
        foreach ($items as $item) {
            $prefix = str_repeat('└─ ', $level);
            $result[] = (object)[
                'id' => $item->id,
                'name' => $prefix . $item->$nameField,
                'level' => $level,
                'original' => $item,
            ];
            
            if ($item->$childrenRelation && $item->$childrenRelation->count() > 0) {
                $result = array_merge($result, $this->buildHierarchicalList(
                    $item->$childrenRelation,
                    $nameField,
                    $childrenRelation,
                    $level + 1
                ));
            }
        }
        
        return $result;
    }

    /**
     * Show import form
     */
    public function import()
    {
        dd('teguh');
        return view('admin.assets.import');
    }

    /**
     * Show import form
     */
    public function importcsv()
    {
        dd('teguh');
        return view('admin.assets.import');
    }

    /**
     * Download template file
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="asset_import_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 to ensure Excel opens it correctly
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header row
            fputcsv($file, [
                'name',
                'barcode',
                'asset_tag',
                'serial_number',
                'description',
                'category_name',
                'department_name',
                'location_name',
                'manufacturer',
                'model',
                'model_number',
                'status',
                'condition',
                'purchase_date',
                'purchase_price',
                'current_value',
                'notes'
            ]);
            
            // Sample data row
            fputcsv($file, [
                'Laptop Dell XPS 15',
                'BC001',
                'AT001',
                'SN123456789',
                'Laptop untuk keperluan kantor',
                'Electronics',
                'IT Department',
                'Office Building - Floor 1 - Room 101',
                'Dell',
                'XPS 15',
                'XPS15-2024',
                'available',
                'good',
                '2024-01-15',
                '15000000',
                '12000000',
                'Sample notes'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Process import file
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        
        $data = [];
        $errors = [];
        $successCount = 0;
        $errorCount = 0;
        
        if (($handle = fopen($path, 'r')) !== false) {
            // Skip BOM if present
            $firstLine = fgets($handle);
            if (substr($firstLine, 0, 3) == chr(0xEF).chr(0xBB).chr(0xBF)) {
                rewind($handle);
                fseek($handle, 3);
            } else {
                rewind($handle);
            }
            
            // Read header
            $header = fgetcsv($handle);
            if (!$header) {
                return back()->withErrors(['file' => 'File CSV tidak valid atau kosong.']);
            }
            
            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                if (count($row) < count($header)) {
                    $errors[] = "Baris {$rowNumber}: Jumlah kolom tidak sesuai.";
                    $errorCount++;
                    continue;
                }
                
                $rowData = array_combine($header, $row);
                
                // Skip empty rows
                if (empty(array_filter($rowData))) {
                    continue;
                }
                
                try {
                    // Validate and process row
                    $assetData = $this->processImportRow($rowData, $rowNumber);
                    
                    if ($assetData) {
                        // Generate barcode if not provided
                        if (empty($assetData['barcode'])) {
                            $assetData['barcode'] = Str::upper(Str::random(10));
                        }
                        
                        $assetData['created_by'] = auth()->id();
                        
                        Asset::create($assetData);
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    $errorCount++;
                }
            }
            
            fclose($handle);
        }
        
        $message = "Import selesai. Berhasil: {$successCount}, Gagal: {$errorCount}.";
        
        if (!empty($errors)) {
            return back()
                ->with('import_errors', $errors)
                ->with('warning', $message);
        }
        
        return redirect()
            ->route('assets.index')
            ->with('success', $message);
    }

    /**
     * Process a single import row
     */
    protected function processImportRow(array $rowData, int $rowNumber): ?array
    {
        // Required fields
        if (empty($rowData['name'])) {
            throw new \Exception('Nama asset wajib diisi.');
        }
        
        if (empty($rowData['serial_number'])) {
            throw new \Exception('Serial number wajib diisi.');
        }
        
        // Check if serial number already exists
        if (Asset::where('serial_number', $rowData['serial_number'])->exists()) {
            throw new \Exception('Serial number sudah terdaftar: ' . $rowData['serial_number']);
        }
        
        // Check if barcode already exists (if provided)
        if (!empty($rowData['barcode']) && Asset::where('barcode', $rowData['barcode'])->exists()) {
            throw new \Exception('Barcode sudah terdaftar: ' . $rowData['barcode']);
        }
        
        // Find category by name
        $category = null;
        if (!empty($rowData['category_name'])) {
            $category = Category::where('name', 'like', trim($rowData['category_name']))->first();
            if (!$category) {
                throw new \Exception('Kategori tidak ditemukan: ' . $rowData['category_name']);
            }
        } else {
            throw new \Exception('Nama kategori wajib diisi.');
        }
        
        // Find department by name (optional)
        $department = null;
        if (!empty($rowData['department_name'])) {
            $department = Department::where('department', 'like', trim($rowData['department_name']))->first();
            // If not found, try to find by partial match
            if (!$department) {
                $department = Department::where('department', 'like', '%' . trim($rowData['department_name']) . '%')->first();
            }
        }
        
        // Find location by name (optional)
        $location = null;
        if (!empty($rowData['location_name'])) {
            $location = Location::where('name', 'like', trim($rowData['location_name']))->first();
            // If not found, try to find by partial match
            if (!$location) {
                $location = Location::where('name', 'like', '%' . trim($rowData['location_name']) . '%')->first();
            }
        }
        
        // Validate status
        $status = !empty($rowData['status']) ? strtolower(trim($rowData['status'])) : 'available';
        if (!in_array($status, ['available', 'assigned', 'maintenance', 'disposed', 'reserved'])) {
            $status = 'available';
        }
        
        // Validate condition
        $condition = !empty($rowData['condition']) ? strtolower(trim($rowData['condition'])) : 'good';
        if (!in_array($condition, ['excellent', 'good', 'fair', 'poor', 'critical'])) {
            $condition = 'good';
        }
        
        return [
            'name' => trim($rowData['name']),
            'barcode' => !empty($rowData['barcode']) ? trim($rowData['barcode']) : null,
            'asset_tag' => !empty($rowData['asset_tag']) ? trim($rowData['asset_tag']) : null,
            'serial_number' => trim($rowData['serial_number']),
            'description' => !empty($rowData['description']) ? trim($rowData['description']) : null,
            'category_id' => $category->id,
            'department_id' => $department ? $department->id : null,
            'location_id' => $location ? $location->id : null,
            'manufacturer' => !empty($rowData['manufacturer']) ? trim($rowData['manufacturer']) : null,
            'model' => !empty($rowData['model']) ? trim($rowData['model']) : null,
            'model_number' => !empty($rowData['model_number']) ? trim($rowData['model_number']) : null,
            'status' => $status,
            'condition' => $condition,
            'purchase_date' => !empty($rowData['purchase_date']) ? $rowData['purchase_date'] : null,
            'purchase_price' => !empty($rowData['purchase_price']) ? (float) $rowData['purchase_price'] : null,
            'current_value' => !empty($rowData['current_value']) ? (float) $rowData['current_value'] : null,
            'notes' => !empty($rowData['notes']) ? trim($rowData['notes']) : null,
        ];
    }
}


