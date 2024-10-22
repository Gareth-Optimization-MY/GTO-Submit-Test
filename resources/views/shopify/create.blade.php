<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1">
    <title>Home Page</title>
    <link href="{{ env('APP_URL') }}css/style.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <div class="Polaris-Page">
            <div class="Polaris-Box" style="--pc-box-padding-block-end-xs:var(--p-space-4);--pc-box-padding-block-end-md:var(--p-space-5);--pc-box-padding-block-start-xs:var(--p-space-4);--pc-box-padding-block-start-md:var(--p-space-5);--pc-box-padding-inline-start-xs:var(--p-space-4);--pc-box-padding-inline-start-sm:var(--p-space-0);--pc-box-padding-inline-end-xs:var(--p-space-4);--pc-box-padding-inline-end-sm:var(--p-space-0);position:relative">
              <div class="Polaris-Page-Header--mediumTitle">
                <div class="Polaris-Page-Header__Row">
                  <div class="Polaris-Page-Header__BreadcrumbWrapper">
                    <div class="Polaris-Box Polaris-Box--printHidden" style="--pc-box-max-width:100%;--pc-box-padding-inline-end-xs:var(--p-space-4)">
                      <nav role="navigation">
                        <a class="Polaris-Breadcrumbs__Breadcrumb" href="/app_view?shop={{ $shop }}" data-polaris-unstyled="true">
                          <span class="Polaris-Breadcrumbs__Icon">
                            <span class="Polaris-Icon">
                              <span class="Polaris-Text--root Polaris-Text--visuallyHidden">
                              </span>
                              <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                <path d="M17 9h-11.586l3.293-3.293a.999.999 0 1 0-1.414-1.414l-5 5a.999.999 0 0 0 0 1.414l5 5a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414l-3.293-3.293h11.586a1 1 0 1 0 0-2z">
                                </path>
                              </svg>
                            </span>
                          </span>
                          <span class="Polaris-Text--root Polaris-Text--visuallyHidden">Orders</span>
                        </a>
                      </nav>
                    </div>
                  </div>
                  <div class="Polaris-Page-Header__TitleWrapper">
                    <h1 class="Polaris-Header-Title">Create Report</h1>
                  </div>
                </div>
              </div>
            </div>
            <form action="/store">
                <input type="hidden" name="shop" value="{{ $shop }}">
              <div class="Polaris-LegacyCard">
                <div class="Polaris-LegacyCard__Section">
                    <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                          <div class="Polaris-Label">
                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">Submission Name</label>
                          </div>
                        </div>
                        <div class="Polaris-Connected">
                          <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">
                            <div class="Polaris-TextField">
                              <input id="PolarisTextField1" name="submission_date" placeholder="Submission Name" autocomplete="off" class="Polaris-TextField__Input" type="text" aria-labelledby="PolarisTextField1Label" aria-invalid="false" value="">
                              <div class="Polaris-TextField__Backdrop">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <div class="Polaris-Labelled__LabelWrapper">
                          <div class="Polaris-Label">
                            <label id="PolarisTextField1Label" for="PolarisTextField1" class="Polaris-Label__Text">Submission Name</label>
                          </div>
                        </div>
                        <div class="Polaris-Connected">
                          <div class="Polaris-Connected__Item Polaris-Connected__Item--primary">

                            <div class="Polaris-Select">
                                <select id="PolarisSelect1" class="Polaris-Select__Input"  name="pos_location">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="lastWeek">Last 7 days</option>
                                </select>
                                <div class="Polaris-Select__Content" aria-hidden="true">
                                  <span class="Polaris-Select__SelectedOption">ToAlirazaday</span>
                                </div>
                                <div class="Polaris-Select__Backdrop">
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="Polaris-Labelled__LabelWrapper">
                          <div class="Polaris-Label">
                            <label id="PolarisSelect1Label" for="PolarisSelect1" class="Polaris-Label__Text">Select POS location</label>
                          </div>
                        </div>
                        <div class="Polaris-Select">
                          <select id="PolarisSelect1" class="Polaris-Select__Input" aria-invalid="false" name="pos_location">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="lastWeek">Last 7 days</option>
                          </select>
                          <div class="Polaris-Select__Content" aria-hidden="true">
                            <span class="Polaris-Select__SelectedOption">Yesterday</span>
                            <span class="Polaris-Select__Icon">
                              <span class="Polaris-Icon">
                                <span class="Polaris-Text--root Polaris-Text--visuallyHidden">
                                </span>
                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg" focusable="false" aria-hidden="true">
                                  <path d="M7.676 9h4.648c.563 0 .879-.603.53-1.014l-2.323-2.746a.708.708 0 0 0-1.062 0l-2.324 2.746c-.347.411-.032 1.014.531 1.014Zm4.648 2h-4.648c-.563 0-.878.603-.53 1.014l2.323 2.746c.27.32.792.32 1.062 0l2.323-2.746c.349-.411.033-1.014-.53-1.014Z">
                                  </path>
                                </svg>
                              </span>
                            </span>
                          </div>
                          <div class="Polaris-Select__Backdrop">
                          </div>
                        </div>
                      </div>
                </div>
              </div>
              <div class="Polaris-PageActions">
                <div class="Polaris-LegacyStack Polaris-LegacyStack--spacingTight Polaris-LegacyStack--distributionTrailing">
                  <div class="Polaris-LegacyStack__Item">
                    <button class="Polaris-Button Polaris-Button--primary" aria-disabled="true" type="button" tabindex="-1">
                      <span class="Polaris-Button__Content">
                        <span class="Polaris-Button__Text">Save</span>
                      </span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
</body>
</html>
