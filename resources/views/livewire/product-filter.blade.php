<div>
    <div class="sidebar-filter-overlay"></div><!-- End .sidebar-filter-overlay -->
    <aside class="sidebar-shop sidebar-filter">
        <div class="sidebar-filter-wrapper">
            <div class="widget widget-clean">
                <label><i class="icon-close"></i>Filter</label>
                <a href="#" wire:click="clearFilter" class="sidebar-filter-clear">Bersihkan Filter</a>
            </div><!-- End .widget -->
            <div class="widget widget-collapsible">
                <h3 class="widget-title">
                    <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true" aria-controls="widget-1">
                        Kategori
                    </a>
                </h3><!-- End .widget-title -->

                <div class="collapse show" id="widget-1">
                    <div class="widget-body">
                        <div class="filter-items filter-items-count">
                            @foreach ($categories as $item)
                            <div class="filter-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" wire:model="selectedCategories" value="{{ $item->id }}" id="{{ $item->slug }}">
                                    <label class="custom-control-label" for="{{ $item->slug }}">{{ $item->name }}</label>
                                </div>
                                <span class="item-count">{{ $item->product_count }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget widget-collapsible">
                <h3 class="widget-title">
                    <a data-toggle="collapse" href="#widget-2" role="button" aria-expanded="true" aria-controls="widget-2">
                        Ukuran
                    </a>
                </h3>

                <div class="collapse show" id="widget-2">
                    <div class="widget-body">
                        <div class="filter-items">
                            @foreach ($sizes as $item)
                            <div class="filter-item">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" wire:model="selectedSizes" value="{{ $item->id }}" class="custom-control-input" id="{{ $item->code }}">
                                    <label class="custom-control-label" for="{{ $item->code }}">{{ Str::upper($item->name)
                                        }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="widget widget-collapsible">
                <h3 class="widget-title">
                    <a data-toggle="collapse" href="#widget-3" role="button" aria-expanded="true" aria-controls="widget-3">
                        Warna
                    </a>
                </h3><!-- End .widget-title -->

                <div class="collapse show" id="widget-3">
                    <div class="widget-body">
                        <div class="select-custom">
                            <select wire:model="colorId" class="form-control">
                                <option value="">Semua Warna</option>
                                @foreach ($colors as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div><!-- End .select-custom -->
                    </div><!-- End .widget-body -->
                </div><!-- End .collapse -->
            </div><!-- End .widget -->

            <div class="widget widget-collapsible">
                <h3 class="widget-title">
                    <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true" aria-controls="widget-5">
                        Harga
                    </a>
                </h3><!-- End .widget-title -->

                <div class="collapse show" id="widget-5">
                    <div class="widget-body">
                        <div class="filter-price">
                            <div class="form-group">
                                <label for="min-price">Harga Minimum</label>
                                <input type="number" class="form-control" wire:model.lazy="minimumPrice">
                            </div>
                            <div class="form-group">
                                <label for="max-price">Harga Maksimum</label>
                                <input type="number" class="form-control" wire:model.lazy="maximumPrice">
                            </div>
                        </div><!-- End .filter-price -->
                    </div><!-- End .widget-body -->
                </div><!-- End .collapse -->
            </div><!-- End .widget -->
        </div><!-- End .sidebar-filter-wrapper -->
    </aside><!-- End .sidebar-filter -->
</div>
