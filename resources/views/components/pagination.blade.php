@props(['data'])

<div class="d-flex justify-content-end align-items-center w-100 pagination-container">
    {{-- Container for both elements to ensure they stay on one line --}}
    <div class="d-flex align-items-center">
        
        {{-- Laravel Pagination Links --}}
        <div class="pagination-links-wrapper">
            {{ $data->appends(request()->query())->links() }}
        </div>

        {{-- Rows Per Page - Attached directly to the end of pagination --}}
        <div class="per-page-wrapper ml-n1">
            <ul class="pagination mb-0">
                <li class="page-item disabled d-none d-sm-block">
                    <span class="page-link bg-light text-muted border-left-0" style="height: 38px; display: flex; align-items: center;">
                        Rows:
                    </span>
                </li>
                <li class="page-item">
                    <form method="GET" class="m-0">
                        @foreach(request()->except(['per_page', 'page']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <select name="per_page" class="form-control page-link text-primary font-weight-bold" 
                                onchange="this.form.submit()" 
                                style="height: 38px; 
                                       width: auto; 
                                       border-left-0; 
                                       border-top-left-radius: 0; 
                                       border-bottom-left-radius: 0;
                                       padding-top: 0;
                                       padding-bottom: 0;
                                       line-height: 38px;
                                       cursor: pointer;">
                            @foreach([10, 20, 50, 100] as $num)
                                <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>{{ $num }}</option>
                            @endforeach
                        </select>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* Remove Laravel's default nav margin */
    .pagination-links-wrapper nav {
        display: inline-block;
    }
    
    .pagination-links-wrapper .pagination {
        margin-bottom: 0 !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    /* Remove right rounding from the last link in Laravel pagination */
    .pagination-links-wrapper .page-item:last-child .page-link {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    /* Ensure the select looks like a page-link but behaves like a select */
    .per-page-wrapper .form-control.page-link {
        appearance: auto; /* Show dropdown arrow */
        -webkit-appearance: auto;
        -moz-appearance: auto;
    }
    
    .pagination-container .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
