<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['employee/logintest']                = 'employee/login/logintest';

$route['employee']                          = 'employee/login/index';
$route['employee/login']                    = 'employee/login/check';
$route['employee/dashboard']                = 'employee/dashboard/index';
$route['employee/logout']                   = 'employee/dashboard/logout';

//  --------------------------------- Employee-------- ---------------------------
$route['employee/about']                    = 'employee/dashboard/about';
$route['employee/contactus']                = 'employee/dashboard/contactus';
$route['employee/profile']                  = 'employee/employee/profile';
$route['employee/epas']                     = 'employee/employee/epas';


//---------------------------------- Supervisor ---------------------------------
$route['supervisor']                        = 'employee/login/index';
$route['supervisor/login']                  = 'employee/login/check';
$route['supervisor/dashboard']              = 'employee/dashboard/index';
$route['supervisor/logout']                 = 'employee/dashboard/logout';
$route['supervisor/profile/(:any)']         = 'employee/supervisor/profile/$1';
$route['supervisor/epas']                   = 'employee/employee/epas';


$route['supervisor/interview/lists']        = 'employee/interview/index';
$route['supervisor/subordinates']           = 'employee/subordinates/index';
$route['supervisor/removesubordinates']     = 'employee/subordinates/removesubordinates';
$route['supervisor/subimages']              = 'employee/subordinates/load_images';
$route['supervisor/subordinates/load_subs'] = 'employee/subordinates/load_subs';
$route['supervisor/subordinates/removesub'] = 'employee/subordinates/removesub';
$route['supervisor/subordinates/modalreason'] = 'employee/subordinates/removemodal_sub';
$route['supervisor/subordinates/load_removesubs'] = 'employee/subordinates/load_removesubs';
$route['supervisor/subordinates/getappraisal/(:any)']  = 'employee/subordinates/get_appraisals/$1';
$route['supervisor/subordinates/showanswers/(:any)']   = 'employee/subordinates/get_appraisal_answers/$1';

//RESIGNATIONS
$route['supervisor/epas/resignations']      = 'employee/epas/index';
$route['supervisor/epas/load_resigns']      = 'employee/epas/load_resigns';
$route['supervisor/epas/resignation/grade/(:any)/(:any)'] = 'employee/epas/epas_grading/$1/$2';
// $route['apptype/grade/(:any)/(:any)'] = 'employee/epas/epas_grading/$1/$2';
$route['supervisor/epas/appraissal_type/(:any)'] = 'employee/epas/appraisal_type/$1';
$route['supervisor/epas/submit']            = 'employee/epas/epas_submit';
$route['supervisor/epas/preview/(:any)']    = 'employee/epas/epas_preview/$1';
//EOCS
$route['supervisor/epas/eoc']               = 'employee/epas/eoc';
$route['supervisor/epas/load_eoc/(:any)']   = 'employee/epas/load_eoc/$1';
//INTERVIEW
$route['supervisor/interview/grade/(:any)'] = 'employee/interview/interview_grading/$1';
$route['supervisor/interview/submit']       = 'employee/interview/interview_saving';
//PR
$route['supervisor/pr/request']             = 'employee/personnelrequisition/index';

//EXCEL REPORT
$route['supervisor/report/subordinates']    = 'employee/reports/generate_subordinates';

//announcements 06212023
$route['employee/announcements/(:any)']     = 'employee/dashboard/announcements/$1';
$route['employee/memos/(:any)']             = 'employee/dashboard/memos/$1';
$route['employee/doctor/(:any)']            = 'employee/dashboard/doctor/$1';


//FILTER EMPLOYEES
$route['employee/filter/getbunit']             = 'employee/employee/filter_bunit';
$route['employee/filter/getdept']              = 'employee/employee/filter_dept';
$route['employee/filter/getsect']              = 'employee/employee/filter_sect';
$route['employee/filter/getsubsect']           = 'employee/employee/filter_sub_sect';


//  --------------------------------- Account Settings ---------------------------
$route['employee/accountsettings']          = 'employee/account/accountsettings';
$route['employee/updateusername']           = 'employee/account/update_username';
$route['employee/updateaccount']            = 'employee/account/update_account';
$route['employee/change_user']              = 'employee/account/changeusername';
$route['employee/change_password']          = 'employee/account/changepassword';
$route['employee/change_phone']             = 'employee/account/changephone';
$route['employee/updatepassword']           = 'employee/account/update_password';
$route['employee/update_phone_number']      = 'employee/account/update_phone_number';


// ---------------------------------- SALARY INCREASE -------------------------------------------
$route['employee/simemo']                   = 'employee/salaryincrease/simemo';
$route['employee/si/epas']                  = 'employee/salaryincrease/siepas';
$route['employee/siwizard']                 = 'employee/salaryincrease/siwizard';
$route['employee/sistep/(:any)']            = 'employee/salaryincrease/sistep/$1';
$route['employee/si/showguide/(:any)/(:any)'] = 'employee/salaryincrease/simodal_guide/$1/$2';
$route['employee/si/tagdonestep']           = 'employee/salaryincrease/save_done';
$route['employee/si/savestep1']             = 'employee/salaryincrease/save_step1';
$route['employee/si/rate/(:any)']           = 'employee/salaryincrease/sirate/$1';
$route['employee/si/savestep2']             = 'employee/salaryincrease/save_step2';
$route['employee/si/showrate/(:any)/(:any)'] = 'employee/salaryincrease/show_rating/$1/$2';
$route['employee/si/savecomment']           = 'employee/salaryincrease/save_comment';
// ---------------------------------- END SALARY INCREASE ---------------------------------------



//  --------------------------------- FGT  ------------------------------------------------------
$route['employee/fgt']                          = 'employee/fgt/forfgt';
$route['employee/insertingfgt']                 = 'employee/fgt/fgt_insert';
//  --------------------------------- END FGT ---------------------------------------------------


// --------------------------------- benefits  ---------------------------------------------------
// benefits/search_employee 
$route['employee/search_employee']              = 'employee/benefits/search_employee';
$route['employee/modal_searchemployee/(:any)']  = 'employee/benefits/modal_searchemployee/$1';
$route['employee/updatesearchemployee']         = 'employee/benefits/update_searchemployee';

// benefits/final_completion
$route['employee/final_completion']             = 'employee/benefits/final_completion';
$route['employee/finalcompletion']              = 'employee/benefits/get_all_finalcompletion';
$route['employee/modal_finalcompletion/(:any)'] = 'employee/benefits/modal_final_completion/$1';
$route['employee/finalcompletionupdate']        = 'employee/benefits/update_finalcompletion';

// benefits/all_employees  
$route['employee/all_employees']                = 'employee/benefits/all_employees';
$route['employee/filter_employees/(:any)']      = 'employee/benefits/filter_employes/$1';
$route['employee/filter_all_employees/(:any)']  = 'employee/benefits/load_filter_employes/$1';
$route['employee/filter/bunit']                 = 'employee/benefits/filter_bunit';
$route['employee/filter/dept']                  = 'employee/benefits/filter_dept';
$route['employee/filter/sect']                  = 'employee/benefits/filter_sect';
$route['employee/filter/subsect']               = 'employee/benefits/filter_sub_sect';
$route['employee/allemployees']                 = 'employee/benefits/get_all_employees';
$route['employee/modal_all_employees']          = 'employee/benefits/modal_all_employees';

// benefits/new_employees 
$route['employee/new_employees']                = 'employee/benefits/new_employees';
$route['employee/newemployees']                 = 'employee/benefits/get_new_employees';
$route['employee/modal_new_employees']          = 'employee/benefits/modal_new_employees';
$route['employee/filter_employee/(:any)']       = 'employee/benefits/filter_new_employees/$1';
$route['employee/filter_newemployees/(:any)']   = 'employee/benefits/load_filter_new_employees/$1';

// benefits/blacklisted_employees  
$route['employee/blacklisted_employees']        = 'employee/benefits/blacklisted_employees';
$route['employee/blacklist']                    = 'employee/benefits/getblacklist';

// benefits/jobtransfer  
$route['employee/jobtransfer']                  = 'employee/benefits/jobtransfer';
$route['employee/modal_filter_jobtrans']        = 'employee/benefits/filter_jobtrans';
$route['employee/modal_jobtrans/(:any)']        = 'employee/benefits/modal_jobtransfer/$1';
$route['employee/jobtrans']                     = 'employee/benefits/get_jobtrans';
$route['employee/filter_jobtrans/(:any)']       = 'employee/benefits/filter_transfer/$1';
$route['employee/filter_jobtransfer/(:any)']    = 'employee/benefits/load_filter_transfer/$1';

// benefits/inactive_employees  
$route['employee/inactive_employees']           = 'employee/benefits/inactive_employees';
$route['employee/inactiveemployees']            = 'employee/benefits/get_inactive_employees';

// benefits/qbe_reports  
$route['employee/qbe_reports']                   = 'employee/benefits/qbe_reports';
$route['employee/qbe/newemployee']               = 'employee/benefits/newemployee';
$route['employee/qbe/excelreport']               = 'employee/benefits/excelnewemployeereport';
$route['employee/qbe/pdfreport']                 = 'employee/benefits/pdfnewemployeereport';
$route['employee/qbe/nobenefits']                = 'employee/benefits/nobenefits';
$route['employee/qbe/pdfreportbenefits']         = 'employee/benefits/pdfnobenefits';
$route['employee/qbe/regularcontractual']        = 'employee/benefits/regularcontractual';
$route['employee/qbe/excelregularcontractual']   = 'employee/benefits/excelregularcontractualreport';
$route['employee/qbe/employeetype']              = 'employee/benefits/employeetype';
$route['employee/qbe/excelreportemptype']        = 'employee/benefits/excelreportemptype';
$route['employee/search/searchswitch']           = 'employee/benefits/searchswitch';
$route['employee/search/searchemployee']         = 'employee/benefits/searchemp';
$route['employee/search/searchbyname']           = 'employee/benefits/searchbyname';
$route['employee/search/searchallemployee/(:any)'] = 'employee/benefits/searchallbyname/$1';
//  -------------------------------------  END BENEFITS ---------------------------------------------------


//  -------------------------------------  PAYROLL --------------------------------------------------------
// PAYROLL / SEARCH
$route['employee/payroll/searchs']                  = 'employee/payroll/profile';
$route['employee/payroll/noresult']                 = 'employee/payroll/noresult';
$route['employee/payroll/searches']                 = 'employee/payroll/search';
$route['employee/payroll/search_result']            = 'employee/payroll/search_results';
// PAYROLL UPDATE PAYROLL NUMBER
$route['employee/payroll/updatepid']                = 'employee/payroll/save_payrollid';
$route['employee/payroll/save_charging_company']    = 'employee/payroll/save_charging_company';
$route['employee/payroll/upload']                   = 'employee/payroll/upload_remittances';
// PAYROLL / NEW EMPLOYEES
$route['employee/payroll/new']                      = 'employee/payroll/newemployees';
$route['employee/payroll/load_newemployees']        = 'employee/payroll/load_newemployees';
$route['employee/payroll/filter_newemployee/(:any)'] = 'employee/payroll/filter_newemployee/$1';
$route['employee/payroll/filternewemployees/(:any)'] = 'employee/payroll/load_filter_new_employee/$1';
// PAYROLL / JOB TRANSFER
$route['employee/payroll/transfer']                 = 'employee/payroll/transfer';
$route['employee/payroll/load_transfer']            = 'employee/payroll/load_transfer';
$route['employee/payroll/filter_transfer/(:any)']   = 'employee/payroll/filter_transfer/$1';
$route['employee/payroll/load_transfer/(:any)']     = 'employee/payroll/load_filter_transfer/$1';
// PAYROLL / ALL EMPLOYEES
$route['employee/payroll/employees']                = 'employee/payroll/employees';
$route['employee/payroll/load_employees']           = 'employee/payroll/load_employees';
$route['employee/payroll/filter_employee/(:any)']   = 'employee/payroll/filter_employee/$1';
$route['employee/payroll/filteremployees/(:any)']   = 'employee/payroll/load_filter_active_employee/$1';
// PAYROLL / BLACKLISTED
$route['employee/payroll/blacklisted']              = 'employee/payroll/blacklisted';
$route['employee/payroll/load_blacklisted']         = 'employee/payroll/load_blacklisted';
// PAYROLL / POSITION LEVELING
$route['employee/payroll/poslevel']                 = 'employee/payroll/position_leveling';
// PAYROLL / PCC
$route['employee/payroll/pcc/(:any)']               = 'employee/payroll/pcc/$1';
$route['employee/payroll/pccemployees/(:any)']      = 'employee/payroll/load_pcc_with_employees/$1';
$route['employee/reports/pcc_export/(:any)']        = 'employee/reports/generate_pcc_employees/$1';
// PAYROLL / FORM FILTERS
$route['employee/payroll/formfilter']               = 'employee/payroll/form_filter';
$route['employee/payroll/viewcsv']                   = 'employee/payroll/view_csv';
//  -------------------------------------  END PAYROLL -------------------------------------------------------


//  -------------------------------------  ACCOUNTING --------------------------------------------------------
$route['employee/accounting/masterfile']            = 'employee/accounting/employees';
$route['employee/accounting/allemployees']          = 'employee/accounting/load_employees';
$route['employee/accounting/formfilter']            = 'employee/accounting/form_filter';
$route['employee/accounting/filter_employee/(:any)'] = 'employee/accounting/filter_employee/$1';
$route['employee/accounting/filteremployees/(:any)'] = 'employee/accounting/load_filter_employee/$1';
//  -------------------------------------  END ACCOUNTING ----------------------------------------------------

// -----------------------------------------------------------------------------------------------------------
// --------------------------------------  PLACEMENT ---------------------------------------------------------

// PLACEMENT MASTERFILE 
$route['masterfile/listemployees']                   = 'placement/masterfile/list_employees';
$route['masterfile/modal_employees']                 = 'placement/masterfile/modal_employees';
$route['masterfile/filter_employees/(:any)/(:any)']         = 'placement/masterfile/filter_employees/$1/$2';
$route['masterfile/filter_employes/(:any)/(:any)']          = 'placement/masterfile/list_filter_employees/$1/$2';
$route['masterfile/excel_per_bu']                           = 'placement/masterfile/excel_all_employee_per_bu';
$route['masterfile/modal_blacklist/(:any)']                  = 'placement/masterfile/modal_blacklisted_employees/$1';
$route['masterfile/blacklistupdate']                          = 'placement/masterfile/update_blacklist';
$route['masterfile/filter_per_year/(:any)']                     = 'placement/masterfile/filter_by_year/$1';
$route['masterfile/filter_per_level/(:any)']                     = 'placement/masterfile/filter_by_level/$1';
$route['masterfile/filter_year_solo_parent/(:any)']               = 'placement/masterfile/filter_solo_parent/$1';
$route['edit_jobtransfer/(:any)']                         = 'placement/masterfile/edit_jobtransfer/$1';
$route['masterfile/findsupervisor']                         = 'placement/masterfile/findEmployeeSupervisor';
$route['masterfile/getlevel']                               = 'placement/masterfile/getLevel';
// END PLACEMENT MASTERFILE 

// FILTER PLACEMENT
$route['masterfile/filter/getbunit']             = 'placement/masterfile/filter_bunit';
$route['masterfile/filter/getdept']              = 'placement/masterfile/filter_dept';
$route['masterfile/filter/getsect']              = 'placement/masterfile/filter_sect';
$route['masterfile/filter/getsubsect']           = 'placement/masterfile/filter_sub_sect';
// END FILTER PLACEMENT



// SEARCH
$route['placement/search/employee']                  = 'placement/search/load_search_employee';
$route['placement/search/applicant']                 = 'placement/search/load_search_applicant';
// END SEARCH


// loyalty awardees
$route['placement/masterfile/entry']               = 'placement/masterfile/loyalty_entry';
$route['placement/masterfile/list']               = 'placement/masterfile/loyalty_list';
$route['placement/masterfile/search']               = 'placement/masterfile/loyalty_search';
$route['placement/masterfile/report']               = 'placement/masterfile/loyalty_report';
$route['placement/masterfile/loyalty_awardee/(:any)']         = 'placement/masterfile/filter_by_year_loyalty/$1';



//  -------------------------------------  END OF PLACEMENT ----------------------------------------------------
// -----------------------------------------------------------------------------------------------------------





//  -------------------------------------  Promo --------------------------------------------------------

// Blacklist
$route['promo/getBlacklist']                = 'promo/blacklist/getBlacklist';
$route['promo/update_blacklist']            = 'promo/blacklist/update_blacklist';
$route['promo/save_bl_update']              = 'promo/blacklist/save_bl_update';
$route['promo/checkBl']                     = 'promo/blacklist/checkBl';
$route['promo/checkBlt']                    = 'promo/blacklist/checkBlt';
$route['promo/addCheckBl']                  = 'promo/blacklist/addCheckBl';
$route['promo/addCheckBlt']                 = 'promo/blacklist/addCheckBlt';
$route['promo/addBl']                       = 'promo/blacklist/addBl';
$route['promo/addManualBl']                 = 'promo/blacklist/addManualBl';
$route['promo/reportedbyBl']                = 'promo/blacklist/reportedbyBl';
$route['promo/save_bl']                     = 'promo/blacklist/save_bl';
// End Blacklist

// Clearance
$route['promo/clearanceList']               = 'promo/clearance/clearanceList';
$route['promo/clearanceDetails']            = 'promo/clearance/clearanceDetails';
$route['promo/nameSearch']                  = 'promo/clearance/nameSearch';
$route['promo/reprintClearance']            = 'promo/clearance/reprintClearance';
$route['promo/getName_clearance']           = 'promo/clearance/getName_clearance';
$route['promo/secureClearance']             = 'promo/clearance/secureClearance';
$route['promo/browseEpas']                  = 'promo/clearance/browseEpas';
$route['promo/uploadClearance']             = 'promo/clearance/uploadClearance';
// End Clearance

// Profile
$route['promo/profilePic']                  = 'promo/profile/profilePic';
$route['promo/profileData']                 = 'promo/profile/profileData';
$route['promo/basicInfo']                   = 'promo/profile/basicInfo';
$route['promo/contactInfo']                 = 'promo/profile/contactInfo';
$route['promo/famEducBackground']           = 'promo/profile/famEducBackground';
$route['promo/modal_form']                  = 'promo/profile/modal_form';
$route['promo/save_modal_form']             = 'promo/profile/save_modal_form';
$route['promo/viewAppraisal']               = 'promo/profile/viewAppraisal';
$route['promo/viewExam']                    = 'promo/profile/viewExam';
$route['promo/viewExam_history']            = 'promo/profile/viewExam_history';
$route['promo/viewApp_details']             = 'promo/profile/viewApp_details';
$route['promo/viewInt_details']             = 'promo/profile/viewInt_details';
$route['promo/appHistory']                  = 'promo/profile/appHistory';
$route['promo/viewContract']                = 'promo/profile/viewContract';
$route['promo/editContract']                = 'promo/profile/editContract';
$route['promo/uploadContract']              = 'promo/profile/uploadContract';
$route['promo/getSelect']                   = 'promo/profile/getSelect';
$route['promo/save_editContract']           = 'promo/profile/save_editContract';
$route['promo/contractFile']                = 'promo/profile/contractFile';
$route['promo/save_uploadContract']         = 'promo/profile/save_uploadContract';
$route['promo/benefits']                    = 'promo/profile/benefits';
$route['promo/supervisorList']              = 'promo/profile/supervisorList';
$route['promo/save_supervisor_form']        = 'promo/profile/save_supervisor_form';
$route['promo/docFile_view']                = 'promo/profile/docFile_view';
$route['promo/docFile_upload']              = 'promo/profile/docFile_upload';
$route['promo/remarks']                     = 'promo/profile/remarks';
// End Profile

// Contract
$route['promo/eocList']                     = 'promo/contract/eocList';
$route['promo/proceed']                     = 'promo/contract/proceed';
$route['promo/save_uploadClearance']        = 'promo/contract/save_uploadClearance';
$route['promo/renewContract']               = 'promo/contract/renewContract';
$route['promo/setSession']                  = 'promo/contract/setSession';
$route['promo/generatePermitForm']          = 'promo/contract/generatePermitForm';
$route['promo/generateContractForm']        = 'promo/contract/generateContractForm';
$route['promo/savePermit']                  = 'promo/contract/savePermit';
$route['promo/saveContract']                = 'promo/contract/saveContract';
$route['promo/transferRateForm']            = 'promo/contract/transferRateForm';
$route['promo/checkStores']                 = 'promo/contract/checkStores';
$route['promo/transferRateSave']            = 'promo/contract/transferRateSave';
// End Contract

// Pdf
$route['promo/generateClearance']           = 'promo/pdf/generateClearance';
$route['promo/generateContract']            = 'promo/pdf/generateContract';
$route['promo/generatePermit']              = 'promo/pdf/generatePermit';
$route['promo/viewPdf']                     = 'promo/pdf/viewPdf';
$route['promo/generateDueContractsPDF']     = 'promo/pdf/generateDueContractsPDF';
$route['promo/generateTermRepPdf']          = 'promo/pdf/generateTermRepPdf';
$route['promo/generateTermContract']        = 'promo/pdf/generateTermContract';
// End Pdf

// Outlet
$route['promo/changeOutletHistory']         = 'promo/outlet/changeOutletHistory';
$route['promo/changeOutletForm']            = 'promo/outlet/changeOutletForm';
$route['promo/changeOutlet']                = 'promo/outlet/changeOutlet';
$route['promo/outletClearance']             = 'promo/outlet/outletClearance';
// End Outlet

// Promo
$route['promo/getPromoDetails']             = 'promo/promo/getPromoDetails';
$route['promo/masterfile']                  = 'promo/promo/masterfile';
$route['promo/searchApplicant']             = 'promo/promo/searchApplicant';
$route['promo/tagToRecruitment']            = 'promo/promo/tagToRecruitment';
// End Promo

// Resignation
$route['promo/resignationList']             = 'promo/resignation/resignationList';
$route['promo/uploadLetter']                = 'promo/resignation/uploadLetter';
$route['promo/save_uploadLetter']           = 'promo/resignation/save_uploadLetter';
$route['promo/addResignationForm']          = 'promo/resignation/addResignationForm';
$route['promo/save_addResignation']         = 'promo/resignation/save_addResignation';
$route['promo/tagResignationTable']         = 'promo/resignation/tagResignationTable';
$route['promo/tagResignation']              = 'promo/resignation/tagResignation';
// End Resignation

// User
$route['promo/save_userAccount']            = 'promo/user/save_userAccount';
$route['promo/promoUserAccessRoles']        = 'promo/user/promoUserAccessRoles';
$route['promo/accessRoles']                 = 'promo/user/accessRoles';
$route['promo/managePromoUserAccounts']     = 'promo/user/managePromoUserAccounts';
$route['promo/userAccount']                 = 'promo/user/userAccount';
$route['promo/managePromoInchargeAccounts'] = 'promo/user/managePromoInchargeAccounts';
$route['promo/updatePromoInchargeAccounts'] = 'promo/user/updatePromoInchargeAccounts';
$route['promo/addPromoInchargeAccount']     = 'promo/user/addPromoInchargeAccount';
$route['promo/updateUserAccount']           = 'promo/user/updateUserAccount';
// End User

// Setup
$route['promo/supervisorDetails']           = 'promo/setup/supervisorDetails';
$route['promo/subordinates']                = 'promo/setup/subordinates';
$route['promo/updateSubordinates']          = 'promo/setup/updateSubordinates';
$route['promo/addSubordinatesForm']         = 'promo/setup/addSubordinatesForm';
$route['promo/generateSub']                 = 'promo/setup/generateSub';
$route['promo/companyAgency']               = 'promo/setup/companyAgency';
$route['promo/productCompany']              = 'promo/setup/productCompany';
$route['promo/updateCompanyAgency']         = 'promo/setup/updateCompanyAgency';
$route['promo/updateProductCompany']        = 'promo/setup/updateProductCompany';
$route['promo/companyAgencyList']           = 'promo/setup/companyAgencyList';
$route['promo/productCompanyList']          = 'promo/setup/productCompanyList';
$route['promo/agencyList']                  = 'promo/setup/agencyList';
$route['promo/companyList']                 = 'promo/setup/companyList';
$route['promo/productList']                 = 'promo/setup/productList';
$route['promo/updateForm']                  = 'promo/setup/updateForm';
$route['promo/updateAgency']                = 'promo/setup/updateAgency';
$route['promo/updateCompany']               = 'promo/setup/updateCompany';
$route['promo/updateProduct']               = 'promo/setup/updateProduct';
$route['promo/departmentList']              = 'promo/setup/departmentList';
$route['promo/updateDepartment']            = 'promo/setup/updateDepartment';
$route['promo/buList']                      = 'promo/setup/buList';
$route['promo/updateBu']                    = 'promo/setup/updateBu';
// End Setup

// Reports
$route['promo/generateQbe']                 = 'promo/reports/generateQbe';
$route['promo/generatePromoStat']           = 'promo/reports/generatePromoStat';
$route['promo/generateMonthlyStat']         = 'promo/reports/generateMonthlyStat';
$route['promo/generateAnnualStat']          = 'promo/reports/generateAnnualStat';
$route['promo/generateDueContractsExcel']   = 'promo/reports/generateDueContractsExcel';
$route['promo/generateDutySched']           = 'promo/reports/generateDutySched';
$route['promo/dutySchedList']               = 'promo/reports/dutySchedList';
$route['promo/dutySchedListData']           = 'promo/reports/dutySchedListData';
$route['promo/generateTermRepExcel']        = 'promo/reports/generateTermRepExcel';
$route['promo/termRepList']                 = 'promo/reports/termRepList';
$route['promo/newPromo']                    = 'promo/reports/newPromo';
$route['promo/promoStat']                   = 'promo/reports/promoStat';
$route['promo/generateStatRep']             = 'promo/reports/generateStatRep';
$route['promo/failedEpas']                  = 'promo/reports/failedEpas';
// End Reports

// Utility
$route['promo/logs']                        = 'promo/logs/logs';
$route['promo/logsAdmin']                   = 'promo/logs/logsAdmin';
// End Utility

// Page
$route['promo']                             = 'promo/page/menu';
$route['promo/searchPromo']                 = 'promo/page/searchPromo';
$route['promo/logout']                      = 'promo/page/logout';
$route['promo/page/(:any)/(:any)']          = 'promo/page/menu/$1/$2';
$route['promo/page/(:any)/(:any)/(:any)']   = 'promo/page/menu/$1/$2/$3';
//  -------------------------------------  END Promo ----------------------------------------------------

$route['placement']                                 = 'placement/dashboard';

$route['default_controller']                        = 'login';

$route['404_override']                              = 'pagenotfound';
$route['translate_uri_dashes']                      = FALSE;
