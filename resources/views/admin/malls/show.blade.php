@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<!--begin::Content-->
	<div id="kt_app_content" class="app-content">
		<!--begin::Content container-->
		<div id="kt_app_content_container" class="app-container container-fluid">
			<!--begin::Row-->
			<div class="row g-5 g-xl-10">
				<!--begin::Col-->
				<div class="col-xxl-6 mb-md-5 mb-xl-10">
					<!--begin::Row-->
					<div class="row g-5 g-xl-10">
						<!--begin::Col-->
						<div class="col-md-6 col-xl-6 mb-xxl-10">
							<!--begin::Card widget 8-->
							<div class="card overflow-hidden h-md-50 mb-5 mb-xl-10">
								<!--begin::Card body-->
								<div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
									<!--begin::Statistics-->
									<div class="mb-4 px-9">
										<!--begin::Info-->
										<div class="d-flex align-items-center mb-2">
											<!--begin::Currency-->
											<span class="fs-4 fw-semibold text-gray-400 align-self-start me-1&gt;">$</span>
											<!--end::Currency-->
											<!--begin::Value-->
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1">69,700</span>
											<!--end::Value-->
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.2%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400">Total Online Sales</span>
										<!--end::Description-->
									</div>
									<!--end::Statistics-->
									<!--begin::Chart-->
									<div id="kt_card_widget_8_chart" class="min-h-auto" style="height: 125px"></div>
									<!--end::Chart-->
								</div>
								<!--end::Card body-->
							</div>
							<!--end::Card widget 8-->
							<!--begin::Card widget 5-->
							<div class="card card-flush h-md-50 mb-xl-10">
								<!--begin::Header-->
								<div class="card-header pt-5">
									<!--begin::Title-->
									<div class="card-title d-flex flex-column">
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Amount-->
											<span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">1,836</span>
											<!--end::Amount-->
											<!--begin::Badge-->
											<span class="badge badge-light-danger fs-base">
											<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>2.2%</span>
											<!--end::Badge-->
										</div>
										<!--end::Info-->
										<!--begin::Subtitle-->
										<span class="text-gray-400 pt-1 fw-semibold fs-6">Total Sales</span>
										<!--end::Subtitle-->
									</div>
									<!--end::Title-->
								</div>
								<!--end::Header-->
								<!--begin::Card body-->
								<div class="card-body d-flex align-items-end pt-0">
									<!--begin::Progress-->
									<div class="d-flex align-items-center flex-column mt-3 w-100">
										<div class="d-flex justify-content-between w-100 mt-auto mb-2">
											<span class="fw-bolder fs-6 text-dark">1,048 to Goal</span>
											<span class="fw-bold fs-6 text-gray-400">62%</span>
										</div>
										<div class="h-8px mx-3 w-100 bg-light-success rounded">
											<div class="bg-success rounded h-8px" role="progressbar" style="width: 62%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
										</div>
									</div>
									<!--end::Progress-->
								</div>
								<!--end::Card body-->
							</div>
							<!--end::Card widget 5-->
						</div>
						<!--end::Col-->
						<!--begin::Col-->
						<div class="col-md-6 col-xl-6 mb-xxl-10">
							<!--begin::Card widget 9-->
							<div class="card overflow-hidden h-md-50 mb-5 mb-xl-10">
								<!--begin::Card body-->
								<div class="card-body d-flex justify-content-between flex-column px-0 pb-0">
									<!--begin::Statistics-->
									<div class="mb-4 px-9">
										<!--begin::Statistics-->
										<div class="d-flex align-items-center mb-2">
											<!--begin::Value-->
											<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1">29,420</span>
											<!--end::Value-->
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.6%</span>
											<!--end::Label-->
										</div>
										<!--end::Statistics-->
										<!--begin::Description-->
										<span class="fs-6 fw-semibold text-gray-400">Total Online Visitors</span>
										<!--end::Description-->
									</div>
									<!--end::Statistics-->
									<!--begin::Chart-->
									<div id="kt_card_widget_9_chart" class="min-h-auto" style="height: 125px"></div>
									<!--end::Chart-->
								</div>
								<!--end::Card body-->
							</div>
							<!--end::Card widget 9-->
							<!--begin::Card widget 7-->
							<div class="card card-flush h-md-50 mb-xl-10">
								<!--begin::Header-->
								<div class="card-header pt-5">
									<!--begin::Title-->
									<div class="card-title d-flex flex-column">
										<!--begin::Amount-->
										<span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">6.3k</span>
										<!--end::Amount-->
										<!--begin::Subtitle-->
										<span class="text-gray-400 pt-1 fw-semibold fs-6">Total New Customers</span>
										<!--end::Subtitle-->
									</div>
									<!--end::Title-->
								</div>
								<!--end::Header-->
								<!--begin::Card body-->
								<div class="card-body d-flex flex-column justify-content-end pe-0">
									<!--begin::Title-->
									<span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Today’s Heroes</span>
									<!--end::Title-->
									<!--begin::Users group-->
									<div class="symbol-group symbol-hover flex-nowrap">
										<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
											<span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
										</div>
										<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
											<img alt="Pic" src="/assets/media/avatars/300-11.jpg" />
										</div>
										<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
											<span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
										</div>
										<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
											<img alt="Pic" src="/assets/media/avatars/300-2.jpg" />
										</div>
										<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
											<span class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
										</div>
										<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
											<img alt="Pic" src="/assets/media/avatars/300-12.jpg" />
										</div>
										<a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
											<span class="symbol-label bg-light text-gray-400 fs-8 fw-bold">+42</span>
										</a>
									</div>
									<!--end::Users group-->
								</div>
								<!--end::Card body-->
							</div>
							<!--end::Card widget 7-->
						</div>
						<!--end::Col-->
					</div>
					<!--end::Row-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xxl-6 mb-5 mb-xl-10">
					<!--begin::Maps widget 1-->
					<div class="card card-flush h-md-100">
						<!--begin::Header-->
						<div class="card-header pt-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">World Sales</span>
								<span class="text-gray-400 pt-2 fw-semibold fs-6">Top Selling Countries</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu 3-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
									<!--begin::Heading-->
									<div class="menu-item px-3">
										<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
									</div>
									<!--end::Heading-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Create Invoice</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link flex-stack px-3">Create Payment
										<span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
											<i class="ki-outline ki-information fs-6"></i>
										</span></a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Generate Bill</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
										<a href="#" class="menu-link px-3">
											<span class="menu-title">Subscription</span>
											<span class="menu-arrow"></span>
										</a>
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Plans</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Billing</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Statements</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content px-3">
													<!--begin::Switch-->
													<label class="form-check form-switch form-check-custom form-check-solid">
														<!--begin::Input-->
														<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
														<!--end::Input-->
														<!--end::Label-->
														<span class="form-check-label text-muted fs-6">Recuring</span>
														<!--end::Label-->
													</label>
													<!--end::Switch-->
												</div>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3 my-1">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 3-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body d-flex flex-center">
							<!--begin::Map container-->
							<div id="kt_maps_widget_1_map" class="w-100 h-350px"></div>
							<!--end::Map container-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Maps widget 1-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-10 g-xl-10">
				<!--begin::Col-->
				<div class="col-xl-4 mb-xl-10">
					<!--begin::Engage widget 1-->
					<div class="card h-md-100" dir="ltr">
						<!--begin::Body-->
						<div class="card-body d-flex flex-column flex-center">
							<!--begin::Heading-->
							<div class="mb-2">
								<!--begin::Title-->
								<h1 class="fw-semibold text-gray-800 text-center lh-lg">Have you tried
								<br />new
								<span class="fw-bolder">Invoice Manager ?</span></h1>
								<!--end::Title-->
								<!--begin::Illustration-->
								<div class="py-10 text-center">
									<img src="/assets/media/svg/illustrations/easy/2.svg" class="theme-light-show w-200px" alt="" />
									<img src="/assets/media/svg/illustrations/easy/2-dark.svg" class="theme-dark-show w-200px" alt="" />
								</div>
								<!--end::Illustration-->
							</div>
							<!--end::Heading-->
							<!--begin::Links-->
							<div class="text-center mb-1">
								<!--begin::Link-->
								<a class="btn btn-sm btn-primary me-2" href="../../demo49/dist/apps/ecommerce/customers/listing.html">Try now</a>
								<!--end::Link-->
								<!--begin::Link-->
								<a class="btn btn-sm btn-light" href="../../demo49/dist/apps/invoices/view/invoice-1.html">Learn more</a>
								<!--end::Link-->
							</div>
							<!--end::Links-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Engage widget 1-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-4 mb-xl-10">
					<!--begin::Chart widget 5-->
					<div class="card card-flush h-md-100">
						<!--begin::Header-->
						<div class="card-header flex-nowrap pt-5">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">Top Selling Categories</span>
								<span class="text-gray-400 pt-2 fw-semibold fs-6">8k social visitors</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu 2-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator mb-3 opacity-75"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Ticket</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Customer</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
										<!--begin::Menu item-->
										<a href="#" class="menu-link px-3">
											<span class="menu-title">New Group</span>
											<span class="menu-arrow"></span>
										</a>
										<!--end::Menu item-->
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Admin Group</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Staff Group</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Member Group</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Contact</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator mt-3 opacity-75"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content px-3 py-3">
											<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
										</div>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 2-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-5 ps-6">
							<div id="kt_charts_widget_5" class="min-h-auto"></div>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Chart widget 5-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-4 mb-5 mb-xl-10">
					<!--begin::List widget 6-->
					<div class="card card-flush h-md-100">
						<!--begin::Header-->
						<div class="card-header pt-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-gray-800">Top Selling Products</span>
								<span class="text-gray-400 mt-1 fw-semibold fs-6">8k social visitors</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<a href="../../demo49/dist/apps/ecommerce/catalog/categories.html" class="btn btn-sm btn-light">View All</a>
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-4">
							<!--begin::Table container-->
							<div class="table-responsive">
								<!--begin::Table-->
								<table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
									<!--begin::Table head-->
									<thead>
										<tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
											<th class="p-0 w-50px pb-1">ITEM</th>
											<th class="ps-0 min-w-140px"></th>
											<th class="text-end min-w-140px p-0 pb-1">TOTAL PRICE</th>
										</tr>
									</thead>
									<!--end::Table head-->
									<!--begin::Table body-->
									<tbody>
										<tr>
											<td>
												<img src="/assets/media/stock/ecommerce/210.gif" class="w-50px" alt="" />
											</td>
											<td class="ps-0">
												<a href="../../demo49/dist/apps/ecommerce/sales/details.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">Elephant 1802</a>
												<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Item: #XDG-2347</span>
											</td>
											<td>
												<span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">$72.00</span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="/assets/media/stock/ecommerce/215.gif" class="w-50px" alt="" />
											</td>
											<td class="ps-0">
												<a href="../../demo49/dist/apps/ecommerce/sales/details.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">Red Laga</a>
												<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Item: #XDG-2347</span>
											</td>
											<td>
												<span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">$45.00</span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="/assets/media/stock/ecommerce/209.gif" class="w-50px" alt="" />
											</td>
											<td class="ps-0">
												<a href="../../demo49/dist/apps/ecommerce/sales/details.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">RiseUP</a>
												<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Item: #XDG-2347</span>
											</td>
											<td>
												<span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">$168.00</span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="/assets/media/stock/ecommerce/214.gif" class="w-50px" alt="" />
											</td>
											<td class="ps-0">
												<a href="../../demo49/dist/apps/ecommerce/sales/details.html" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6 text-start pe-0">Yellow Stone</a>
												<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Item: #XDG-2347</span>
											</td>
											<td>
												<span class="text-gray-800 fw-bold d-block fs-6 ps-0 text-end">$72.00</span>
											</td>
										</tr>
									</tbody>
									<!--end::Table body-->
								</table>
							</div>
							<!--end::Table-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::List widget 6-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-10">
				<!--begin::Col-->
				<div class="col-xxl-4 mb-xxl-10">
					<!--begin::List widget 7-->
					<div class="card card-flush h-md-100">
						<!--begin::Header-->
						<div class="card-header py-7">
							<!--begin::Statistics-->
							<div class="m-0">
								<!--begin::Heading-->
								<div class="d-flex align-items-center mb-2">
									<!--begin::Title-->
									<span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2">0.37%</span>
									<!--end::Title-->
									<!--begin::Badge-->
									<span class="badge badge-light-danger fs-base">
									<i class="ki-outline ki-arrow-up fs-5 text-danger ms-n1"></i>8.02%</span>
									<!--end::Badge-->
								</div>
								<!--end::Heading-->
								<!--begin::Description-->
								<span class="fs-6 fw-semibold text-gray-400">Online store convertion rate</span>
								<!--end::Description-->
							</div>
							<!--end::Statistics-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu 2-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator mb-3 opacity-75"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Ticket</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Customer</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
										<!--begin::Menu item-->
										<a href="#" class="menu-link px-3">
											<span class="menu-title">New Group</span>
											<span class="menu-arrow"></span>
										</a>
										<!--end::Menu item-->
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Admin Group</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Staff Group</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Member Group</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Contact</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator mt-3 opacity-75"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content px-3 py-3">
											<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
										</div>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 2-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-0">
							<!--begin::Items-->
							<div class="mb-0">
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-5">
										<!--begin::Symbol-->
										<div class="symbol symbol-30px me-5">
											<span class="symbol-label">
												<i class="ki-outline ki-magnifier fs-3 text-gray-600"></i>
											</span>
										</div>
										<!--end::Symbol-->
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Search Retargeting</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Section-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-6 me-3">0.24%</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="d-flex flex-center">
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.4%</span>
											<!--end::label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-5">
										<!--begin::Symbol-->
										<div class="symbol symbol-30px me-5">
											<span class="symbol-label">
												<i class="ki-outline ki-tiktok fs-3 text-gray-600"></i>
											</span>
										</div>
										<!--end::Symbol-->
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Social Retargeting</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Section-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-6 me-3">0.94%</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="d-flex flex-center">
											<!--begin::label-->
											<span class="badge badge-light-danger fs-base">
											<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>9.4%</span>
											<!--end::label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-5">
										<!--begin::Symbol-->
										<div class="symbol symbol-30px me-5">
											<span class="symbol-label">
												<i class="ki-outline ki-sms fs-3 text-gray-600"></i>
											</span>
										</div>
										<!--end::Symbol-->
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Email Retargeting</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Section-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-6 me-3">1.23%</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="d-flex flex-center">
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>0.2%</span>
											<!--end::label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-5">
										<!--begin::Symbol-->
										<div class="symbol symbol-30px me-5">
											<span class="symbol-label">
												<i class="ki-outline ki-icon fs-3 text-gray-600"></i>
											</span>
										</div>
										<!--end::Symbol-->
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Referrals Customers</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Section-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-6 me-3">0.08%</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="d-flex flex-center">
											<!--begin::label-->
											<span class="badge badge-light-danger fs-base">
											<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>0.4%</span>
											<!--end::label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Section-->
									<div class="d-flex align-items-center me-5">
										<!--begin::Symbol-->
										<div class="symbol symbol-30px me-5">
											<span class="symbol-label">
												<i class="ki-outline ki-abstract-25 fs-3 text-gray-600"></i>
											</span>
										</div>
										<!--end::Symbol-->
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Other</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
									</div>
									<!--end::Section-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-6 me-3">0.46%</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="d-flex flex-center">
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>8.3%</span>
											<!--end::label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Item-->
							</div>
							<!--end::Items-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::List widget 7-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xxl-8 mb-5 mb-xl-10">
					<!--begin::Chart widget 13-->
					<div class="card card-flush h-md-100">
						<!--begin::Header-->
						<div class="card-header pt-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">Sales Statistics</span>
								<span class="text-gray-400 pt-2 fw-semibold fs-6">Top Selling Products</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold w-100px py-4" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Remove</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Mute</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-5">
							<!--begin::Chart container-->
							<div id="kt_charts_widget_13_chart" class="w-100 h-325px"></div>
							<!--end::Chart container-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Chart widget 13-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-10 g-xl-10">
				<!--begin::Col-->
				<div class="col-xl-4 mb-xl-10">
					<!--begin::List widget 8-->
					<div class="card card-flush h-xl-100">
						<!--begin::Header-->
						<div class="card-header pt-7 mb-5">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-gray-800">Visits by Country</span>
								<span class="text-gray-400 mt-1 fw-semibold fs-6">20 countries share 97% visits</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<a href="../../demo49/dist/apps/ecommerce/sales/listing.html" class="btn btn-sm btn-light">View All</a>
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-0">
							<!--begin::Items-->
							<div class="m-0">
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Flag-->
									<img src="/assets/media/flags/united-states.svg" class="me-4 w-25px" style="border-radius: 4px" alt="" />
									<!--end::Flag-->
									<!--begin::Section-->
									<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">United States</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-6 me-3 d-block">9,763</span>
											<!--end::Number-->
											<!--begin::Label-->
											<div class="m-0">
												<!--begin::Label-->
												<span class="badge badge-light-success fs-base">
												<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.6%</span>
												<!--end::Label-->
											</div>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Section-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Flag-->
									<img src="/assets/media/flags/brazil.svg" class="me-4 w-25px" style="border-radius: 4px" alt="" />
									<!--end::Flag-->
									<!--begin::Section-->
									<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Brasil</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">All Social Channels</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-6 me-3 d-block">4,062</span>
											<!--end::Number-->
											<!--begin::Label-->
											<div class="m-0">
												<!--begin::Label-->
												<span class="badge badge-light-danger fs-base">
												<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>0.4%</span>
												<!--end::Label-->
											</div>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Section-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Flag-->
									<img src="/assets/media/flags/turkey.svg" class="me-4 w-25px" style="border-radius: 4px" alt="" />
									<!--end::Flag-->
									<!--begin::Section-->
									<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Turkey</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Mailchimp Campaigns</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-6 me-3 d-block">1,680</span>
											<!--end::Number-->
											<!--begin::Label-->
											<div class="m-0">
												<!--begin::Label-->
												<span class="badge badge-light-success fs-base">
												<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>0.2%</span>
												<!--end::Label-->
											</div>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Section-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Flag-->
									<img src="/assets/media/flags/france.svg" class="me-4 w-25px" style="border-radius: 4px" alt="" />
									<!--end::Flag-->
									<!--begin::Section-->
									<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">France</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Impact Radius visits</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-6 me-3 d-block">849</span>
											<!--end::Number-->
											<!--begin::Label-->
											<div class="m-0">
												<!--begin::Label-->
												<span class="badge badge-light-success fs-base">
												<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>4.1%</span>
												<!--end::Label-->
											</div>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Section-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Flag-->
									<img src="/assets/media/flags/india.svg" class="me-4 w-25px" style="border-radius: 4px" alt="" />
									<!--end::Flag-->
									<!--begin::Section-->
									<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">India</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Many Sources</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-6 me-3 d-block">604</span>
											<!--end::Number-->
											<!--begin::Label-->
											<div class="m-0">
												<!--begin::Label-->
												<span class="badge badge-light-danger fs-base">
												<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>8.3%</span>
												<!--end::Label-->
											</div>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Section-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex flex-stack">
									<!--begin::Flag-->
									<img src="/assets/media/flags/sweden.svg" class="me-4 w-25px" style="border-radius: 4px" alt="" />
									<!--end::Flag-->
									<!--begin::Section-->
									<div class="d-flex flex-stack flex-row-fluid d-grid gap-2">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Sweden</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Social Network</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Info-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-6 me-3 d-block">237</span>
											<!--end::Number-->
											<!--begin::Label-->
											<div class="m-0">
												<!--begin::Label-->
												<span class="badge badge-light-success fs-base">
												<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>1.9%</span>
												<!--end::Label-->
											</div>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Section-->
								</div>
								<!--end::Item-->
							</div>
							<!--end::Items-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::LIst widget 8-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-4 mb-xl-10">
					<!--begin::List widget 9-->
					<div class="card card-flush h-xl-100">
						<!--begin::Header-->
						<div class="card-header py-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-gray-800">Social Network Visits</span>
								<span class="text-gray-400 mt-1 fw-semibold fs-6">8k social visitors</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<a href="#" class="btn btn-sm btn-light">View All</a>
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body card-body d-flex justify-content-between flex-column pt-3">
							<!--begin::Item-->
							<div class="d-flex flex-stack">
								<!--begin::Flag-->
								<img src="/assets/media/svg/brand-logos/dribbble-icon-1.svg" class="me-4 w-30px" style="border-radius: 4px" alt="" />
								<!--end::Flag-->
								<!--begin::Section-->
								<div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
									<!--begin::Content-->
									<div class="me-5">
										<!--begin::Title-->
										<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Dribbble</a>
										<!--end::Title-->
										<!--begin::Desc-->
										<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Community</span>
										<!--end::Desc-->
									</div>
									<!--end::Content-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-4 me-3">579</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="m-0">
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.6%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Item-->
							<!--begin::Separator-->
							<div class="separator separator-dashed my-3"></div>
							<!--end::Separator-->
							<!--begin::Item-->
							<div class="d-flex flex-stack">
								<!--begin::Flag-->
								<img src="/assets/media/svg/brand-logos/linkedin-1.svg" class="me-4 w-30px" style="border-radius: 4px" alt="" />
								<!--end::Flag-->
								<!--begin::Section-->
								<div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
									<!--begin::Content-->
									<div class="me-5">
										<!--begin::Title-->
										<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Linked In</a>
										<!--end::Title-->
										<!--begin::Desc-->
										<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Social Media</span>
										<!--end::Desc-->
									</div>
									<!--end::Content-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-4 me-3">2,588</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="m-0">
											<!--begin::Label-->
											<span class="badge badge-light-danger fs-base">
											<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>0.4%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Item-->
							<!--begin::Separator-->
							<div class="separator separator-dashed my-3"></div>
							<!--end::Separator-->
							<!--begin::Item-->
							<div class="d-flex flex-stack">
								<!--begin::Flag-->
								<img src="/assets/media/svg/brand-logos/slack-icon.svg" class="me-4 w-30px" style="border-radius: 4px" alt="" />
								<!--end::Flag-->
								<!--begin::Section-->
								<div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
									<!--begin::Content-->
									<div class="me-5">
										<!--begin::Title-->
										<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Slack</a>
										<!--end::Title-->
										<!--begin::Desc-->
										<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Messanger</span>
										<!--end::Desc-->
									</div>
									<!--end::Content-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-4 me-3">794</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="m-0">
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>0.2%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Item-->
							<!--begin::Separator-->
							<div class="separator separator-dashed my-3"></div>
							<!--end::Separator-->
							<!--begin::Item-->
							<div class="d-flex flex-stack">
								<!--begin::Flag-->
								<img src="/assets/media/svg/brand-logos/youtube-3.svg" class="me-4 w-30px" style="border-radius: 4px" alt="" />
								<!--end::Flag-->
								<!--begin::Section-->
								<div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
									<!--begin::Content-->
									<div class="me-5">
										<!--begin::Title-->
										<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">YouTube</a>
										<!--end::Title-->
										<!--begin::Desc-->
										<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Video Channel</span>
										<!--end::Desc-->
									</div>
									<!--end::Content-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-4 me-3">1,578</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="m-0">
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>4.1%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Item-->
							<!--begin::Separator-->
							<div class="separator separator-dashed my-3"></div>
							<!--end::Separator-->
							<!--begin::Item-->
							<div class="d-flex flex-stack">
								<!--begin::Flag-->
								<img src="/assets/media/svg/brand-logos/instagram-2-1.svg" class="me-4 w-30px" style="border-radius: 4px" alt="" />
								<!--end::Flag-->
								<!--begin::Section-->
								<div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
									<!--begin::Content-->
									<div class="me-5">
										<!--begin::Title-->
										<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Instagram</a>
										<!--end::Title-->
										<!--begin::Desc-->
										<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Social Network</span>
										<!--end::Desc-->
									</div>
									<!--end::Content-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-4 me-3">3,458</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="m-0">
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>8.3%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Item-->
							<!--begin::Separator-->
							<div class="separator separator-dashed my-3"></div>
							<!--end::Separator-->
							<!--begin::Item-->
							<div class="d-flex flex-stack">
								<!--begin::Flag-->
								<img src="/assets/media/svg/brand-logos/facebook-3.svg" class="me-4 w-30px" style="border-radius: 4px" alt="" />
								<!--end::Flag-->
								<!--begin::Section-->
								<div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
									<!--begin::Content-->
									<div class="me-5">
										<!--begin::Title-->
										<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Facebook</a>
										<!--end::Title-->
										<!--begin::Desc-->
										<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Social Network</span>
										<!--end::Desc-->
									</div>
									<!--end::Content-->
									<!--begin::Wrapper-->
									<div class="d-flex align-items-center">
										<!--begin::Number-->
										<span class="text-gray-800 fw-bold fs-4 me-3">2,047</span>
										<!--end::Number-->
										<!--begin::Info-->
										<div class="m-0">
											<!--begin::Label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>1.9%</span>
											<!--end::Label-->
										</div>
										<!--end::Info-->
									</div>
									<!--end::Wrapper-->
								</div>
								<!--end::Section-->
							</div>
							<!--end::Item-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::List widget 9-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-4 mb-5 mb-xl-10">
					<!--begin::Chart widget 14-->
					<div class="card card-flush h-xl-100">
						<!--begin::Header-->
						<div class="card-header pt-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">Departments</span>
								<span class="text-gray-400 pt-2 fw-semibold fs-6">Performance & achievements</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu 3-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
									<!--begin::Heading-->
									<div class="menu-item px-3">
										<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments</div>
									</div>
									<!--end::Heading-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Create Invoice</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link flex-stack px-3">Create Payment
										<span class="ms-2" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference">
											<i class="ki-outline ki-information fs-6"></i>
										</span></a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Generate Bill</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
										<a href="#" class="menu-link px-3">
											<span class="menu-title">Subscription</span>
											<span class="menu-arrow"></span>
										</a>
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Plans</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Billing</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Statements</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<div class="menu-content px-3">
													<!--begin::Switch-->
													<label class="form-check form-switch form-check-custom form-check-solid">
														<!--begin::Input-->
														<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
														<!--end::Input-->
														<!--end::Label-->
														<span class="form-check-label text-muted fs-6">Recuring</span>
														<!--end::Label-->
													</label>
													<!--end::Switch-->
												</div>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3 my-1">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 3-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-5">
							<!--begin::Chart container-->
							<div id="kt_charts_widget_14_chart" class="w-100 h-350px"></div>
							<!--end::Chart container-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Chart widget 14-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
			<!--begin::Row-->
			<div class="row g-5 g-xl-10 g-xl-10">
				<!--begin::Col-->
				<div class="col-xl-4">
					<!--begin::List widget 12-->
					<div class="card card-flush h-xl-100">
						<!--begin::Header-->
						<div class="card-header pt-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-gray-800">Visits by Source</span>
								<span class="text-gray-400 mt-1 fw-semibold fs-6">29.4k visitors</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu 2-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Quick Actions</div>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator mb-3 opacity-75"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Ticket</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Customer</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
										<!--begin::Menu item-->
										<a href="#" class="menu-link px-3">
											<span class="menu-title">New Group</span>
											<span class="menu-arrow"></span>
										</a>
										<!--end::Menu item-->
										<!--begin::Menu sub-->
										<div class="menu-sub menu-sub-dropdown w-175px py-4">
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Admin Group</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Staff Group</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu item-->
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Member Group</a>
											</div>
											<!--end::Menu item-->
										</div>
										<!--end::Menu sub-->
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">New Contact</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator mt-3 opacity-75"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content px-3 py-3">
											<a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
										</div>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu 2-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body d-flex align-items-end">
							<!--begin::Wrapper-->
							<div class="w-100">
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px me-5">
										<span class="symbol-label">
											<i class="ki-outline ki-rocket fs-3 text-gray-600"></i>
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Container-->
									<div class="d-flex align-items-center flex-stack flex-wrap d-grid gap-1 flex-row-fluid">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Direct Source</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Direct link clicks</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Wrapper-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-4 me-3">1,067</span>
											<!--end::Number-->
											<!--begin::Info-->
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.6%</span>
											<!--end::label-->
											<!--end::Info-->
										</div>
										<!--end::Wrapper-->
									</div>
									<!--end::Container-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px me-5">
										<span class="symbol-label">
											<i class="ki-outline ki-tiktok fs-3 text-gray-600"></i>
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Container-->
									<div class="d-flex align-items-center flex-stack flex-wrap d-grid gap-1 flex-row-fluid">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Social Networks</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">All Social Channels</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Wrapper-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-4 me-3">24,588</span>
											<!--end::Number-->
											<!--begin::Info-->
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>4.1%</span>
											<!--end::label-->
											<!--end::Info-->
										</div>
										<!--end::Wrapper-->
									</div>
									<!--end::Container-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px me-5">
										<span class="symbol-label">
											<i class="ki-outline ki-sms fs-3 text-gray-600"></i>
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Container-->
									<div class="d-flex align-items-center flex-stack flex-wrap d-grid gap-1 flex-row-fluid">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Email Newsletter</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Mailchimp Campaigns</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Wrapper-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-4 me-3">794</span>
											<!--end::Number-->
											<!--begin::Info-->
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>0.2%</span>
											<!--end::label-->
											<!--end::Info-->
										</div>
										<!--end::Wrapper-->
									</div>
									<!--end::Container-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px me-5">
										<span class="symbol-label">
											<i class="ki-outline ki-icon fs-3 text-gray-600"></i>
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Container-->
									<div class="d-flex align-items-center flex-stack flex-wrap d-grid gap-1 flex-row-fluid">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Referrals</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Impact Radius visits</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Wrapper-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-4 me-3">6,578</span>
											<!--end::Number-->
											<!--begin::Info-->
											<!--begin::label-->
											<span class="badge badge-light-danger fs-base">
											<i class="ki-outline ki-arrow-down fs-5 text-danger ms-n1"></i>0.4%</span>
											<!--end::label-->
											<!--end::Info-->
										</div>
										<!--end::Wrapper-->
									</div>
									<!--end::Container-->
								</div>
								<!--end::Item-->
								<!--begin::Separator-->
								<div class="separator separator-dashed my-3"></div>
								<!--end::Separator-->
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<!--begin::Symbol-->
									<div class="symbol symbol-30px me-5">
										<span class="symbol-label">
											<i class="ki-outline ki-abstract-25 fs-3 text-gray-600"></i>
										</span>
									</div>
									<!--end::Symbol-->
									<!--begin::Container-->
									<div class="d-flex align-items-center flex-stack flex-wrap d-grid gap-1 flex-row-fluid">
										<!--begin::Content-->
										<div class="me-5">
											<!--begin::Title-->
											<a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Other</a>
											<!--end::Title-->
											<!--begin::Desc-->
											<span class="text-gray-400 fw-semibold fs-7 d-block text-start ps-0">Many Sources</span>
											<!--end::Desc-->
										</div>
										<!--end::Content-->
										<!--begin::Wrapper-->
										<div class="d-flex align-items-center">
											<!--begin::Number-->
											<span class="text-gray-800 fw-bold fs-4 me-3">79,458</span>
											<!--end::Number-->
											<!--begin::Info-->
											<!--begin::label-->
											<span class="badge badge-light-success fs-base">
											<i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>8.3%</span>
											<!--end::label-->
											<!--end::Info-->
										</div>
										<!--end::Wrapper-->
									</div>
									<!--end::Container-->
								</div>
								<!--end::Item-->
								<!--begin::Link-->
								<div class="text-center pt-8 d-1">
									<a href="../../demo49/dist/apps/ecommerce/sales/details.html" class="text-primary opacity-75-hover fs-6 fw-bold">View Store Analytics
									<i class="ki-outline ki-arrow-right fs-3 text-primary"></i></a>
								</div>
								<!--end::Link-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::List widget 12-->
				</div>
				<!--end::Col-->
				<!--begin::Col-->
				<div class="col-xl-8">
					<!--begin::Chart widget 15-->
					<div class="card card-flush h-xl-100">
						<!--begin::Header-->
						<div class="card-header pt-7">
							<!--begin::Title-->
							<h3 class="card-title align-items-start flex-column">
								<span class="card-label fw-bold text-dark">Author Sales</span>
								<span class="text-gray-400 pt-2 fw-semibold fs-6">Statistics by Countries</span>
							</h3>
							<!--end::Title-->
							<!--begin::Toolbar-->
							<div class="card-toolbar">
								<!--begin::Menu-->
								<button class="btn btn-icon btn-color-gray-400 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
									<i class="ki-outline ki-dots-square fs-1 text-gray-300 me-n1"></i>
								</button>
								<!--begin::Menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold w-100px py-4" data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Remove</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Mute</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<a href="#" class="menu-link px-3">Settings</a>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu-->
								<!--end::Menu-->
							</div>
							<!--end::Toolbar-->
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-5">
							<!--begin::Chart container-->
							<div id="kt_charts_widget_15_chart" class="min-h-auto ps-4 pe-6 mb-3 h-350px"></div>
							<!--end::Chart container-->
						</div>
						<!--end::Body-->
					</div>
					<!--end::Chart widget 15-->
				</div>
				<!--end::Col-->
			</div>
			<!--end::Row-->
		</div>
		<!--end::Content container-->
	</div>
<!--end::Content-->
@endsection
