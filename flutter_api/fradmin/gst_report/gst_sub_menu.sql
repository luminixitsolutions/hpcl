-- GST Report sub-menus (menuid = 27)
-- Run this SQL in your database, then add submenu ids to tbl_users_bill.submenuid
-- Example: append ,39,40,41,42,43,44,45 to existing submenuid value

INSERT INTO `tbl_sub_menu` (`id`, `menuid`, `user_id`, `title`, `table_values`, `link`, `srno`, `status`) VALUES
(39, 27, '', 'GST Rate Master', '[
  { "label": "Sr No", "key": "id" },
  { "label": "GST Name", "key": "GstName" },
  { "label": "GST %", "key": "GstPercentage" },
  { "label": "CGST %", "key": "CgstPer" },
  { "label": "SGST %", "key": "SgstPer" },
  { "label": "IGST %", "key": "IgstPer" },
  { "label": "Effective From", "key": "EffectiveFrom" },
  { "label": "Status", "key": "Status" }
]', 'gst_report/gst-rate-master.php?action=list', 1, 1),

(40, 27, '', 'HSN Master', '[
  { "label": "Sr No", "key": "id" },
  { "label": "HSN Code", "key": "HsnCode" },
  { "label": "Description", "key": "Description" },
  { "label": "GST Rate", "key": "GstName" },
  { "label": "GST %", "key": "GstPercentage" },
  { "label": "CGST %", "key": "CgstPer" },
  { "label": "SGST %", "key": "SgstPer" },
  { "label": "IGST %", "key": "IgstPer" },
  { "label": "Status", "key": "Status" }
]', 'gst_report/hsn-master.php?action=list', 2, 1),

(41, 27, '', 'GSTR-1 Report', '[
  { "label": "Sr No", "key": "SrNo" },
  { "label": "Invoice No", "key": "InvoiceNo" },
  { "label": "Invoice Date", "key": "InvoiceDate" },
  { "label": "Customer GSTIN", "key": "CustomerGstin" },
  { "label": "Customer Name", "key": "CustomerName" },
  { "label": "Place of Supply", "key": "PlaceOfSupply" },
  { "label": "Taxable Value (Rs)", "key": "TaxableValue" },
  { "label": "CGST (Rs)", "key": "Cgst" },
  { "label": "SGST (Rs)", "key": "Sgst" },
  { "label": "IGST (Rs)", "key": "Igst" },
  { "label": "Total Invoice Amount (Rs)", "key": "TotalInvoiceAmount" },
  { "label": "Invoice Type", "key": "InvoiceType" }
]', 'gst_report/gstr1-report.php', 3, 1),

(42, 27, '', 'GSTR-3B Report', '[
  { "label": "Sr No", "key": "SrNo" },
  { "label": "GST Rate %", "key": "GstRate" },
  { "label": "Taxable Value (Rs)", "key": "TaxableValue" },
  { "label": "CGST (Rs)", "key": "Cgst" },
  { "label": "SGST (Rs)", "key": "Sgst" },
  { "label": "IGST (Rs)", "key": "Igst" },
  { "label": "Total GST (Rs)", "key": "TotalGst" },
  { "label": "Total Amount (Rs)", "key": "TotalAmount" }
]', 'gst_report/gstr3b-report.php', 4, 1),

(43, 27, '', 'Daily GST Summary', '[
  { "label": "Sr No", "key": "SrNo" },
  { "label": "Date", "key": "Date" },
  { "label": "Total Bills", "key": "TotalBills" },
  { "label": "Taxable Amount (Rs)", "key": "TaxableAmount" },
  { "label": "CGST (Rs)", "key": "Cgst" },
  { "label": "SGST (Rs)", "key": "Sgst" },
  { "label": "IGST (Rs)", "key": "Igst" },
  { "label": "Total GST (Rs)", "key": "TotalGst" },
  { "label": "Grand Total (Rs)", "key": "GrandTotal" }
]', 'gst_report/daily-gst-summary.php', 5, 1),

(44, 27, '', 'Monthly GST Summary', '[
  { "label": "Sr No", "key": "SrNo" },
  { "label": "Month", "key": "Month" },
  { "label": "Invoice Count", "key": "InvoiceCount" },
  { "label": "Taxable Value (Rs)", "key": "TaxableValue" },
  { "label": "CGST (Rs)", "key": "Cgst" },
  { "label": "SGST (Rs)", "key": "Sgst" },
  { "label": "IGST (Rs)", "key": "Igst" },
  { "label": "Total GST (Rs)", "key": "TotalGst" },
  { "label": "Grand Total (Rs)", "key": "GrandTotal" }
]', 'gst_report/monthly-gst-summary.php', 6, 1),

(45, 27, '', 'GST Invoice Register', '[
  { "label": "Sr No", "key": "SrNo" },
  { "label": "Invoice No", "key": "InvoiceNo" },
  { "label": "Invoice Date", "key": "InvoiceDate" },
  { "label": "Customer Name", "key": "CustomerName" },
  { "label": "GSTIN", "key": "Gstin" },
  { "label": "Taxable Value (Rs)", "key": "TaxableValue" },
  { "label": "CGST (Rs)", "key": "Cgst" },
  { "label": "SGST (Rs)", "key": "Sgst" },
  { "label": "IGST (Rs)", "key": "Igst" },
  { "label": "Total Invoice Amount (Rs)", "key": "TotalInvoiceAmount" },
  { "label": "Invoice Type", "key": "InvoiceType" }
]', 'gst_report/gst-invoice-register.php', 7, 1);

-- Grant submenu access to outlet users (update ids as needed)
-- UPDATE tbl_users_bill SET submenuid = CONCAT(submenuid, ',39,40,41,42,43,44,45') WHERE Roll = 5 AND submenuid NOT LIKE '%39%';
