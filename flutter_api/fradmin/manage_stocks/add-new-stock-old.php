<?php
header('Content-Type: text/html; charset=utf-8');
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", sans-serif;
      padding: 10px;
    }

    .page-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #343a40;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
      border: none;
    }

    .table thead {
      background-color: #0d6efd;
      color: #fff;
      white-space: nowrap;
    }

    .btn-custom {
      border-radius: 8px;
      padding: 10px 25px;
      font-weight: 600;
    }

    .total-box {
      font-size: 18px;
      font-weight: 600;
      color: #198754;
    }

    /* Suggestion box fix */
.suggestions {
  position: fixed !important;       /* instead of absolute */
  background: #fff;
  border: 1px solid #ccc;
  z-index: 99999 !important;
  width: auto;
  min-width: 250px;
  max-width: 400px;
  max-height: 220px;
  overflow-y: auto;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  font-size: 14px;
}

/* suggestion items */
.suggestion-item {
  padding: 10px 12px;
  cursor: pointer;
  transition: background 0.2s;
}

.suggestion-item:hover {
  background-color: #f8f9fa;
}

.suggestion-item.bg-light {
  background-color: #e9f2ff !important;
  border-left: 3px solid #0d6efd;
}


    .dropdown-divider {
      margin: 0;
    }

    .table-responsive {
  overflow-x: auto;
 
}

/* Adjust column widths */
#productTable th:nth-child(1),
#productTable td:nth-child(1) {
  width: 5%;
  min-width: 40px;
}

#productTable th:nth-child(2),
#productTable td:nth-child(2) {
  width: 50%;
  min-width: 180px;
}

#productTable th:nth-child(3),
#productTable td:nth-child(3) {
  width: 15%;
  min-width: 90px;
}

#productTable th:nth-child(4),
#productTable td:nth-child(4) {
  width: 10%;
  min-width: 70px;
}

#productTable th:nth-child(5),
#productTable td:nth-child(5) {
  width: 15%;
  min-width: 90px;
}

#productTable th:nth-child(6),
#productTable td:nth-child(6) {
  width: 10%;
  min-width: 70px;
}

/* Make Product input expand more */
.product-search {
  width: 100%;
  min-width: 150px;
}

/* Mobile adjustments */
@media (max-width: 767px) {
  .table th, .table td {
    font-size: 13px;
    padding: 6px 4px;
  }

  #productTable td:nth-child(2) {
    width: 60%;
    min-width: 170px;
  }

  #productTable td:nth-child(3),
  #productTable td:nth-child(4),
  #productTable td:nth-child(5) {
    width: 12%;
    min-width: 60px;
  }

  #productTable td:nth-child(6) {
    width: 10%;
    min-width: 60px;
  }

  .form-control {
    font-size: 13px;
    padding: 6px;
  }

  .btn {
    font-size: 13px;
    padding: 6px 10px;
  }
}


    /* --- Mobile Adjustments --- */
    @media (max-width: 767px) {
      .page-title {
        font-size: 20px;
      }
      .btn-custom {
        padding: 8px 18px;
        font-size: 15px;
      }
      .card {
        padding: 15px !important;
      }
      table th,
      table td {
        font-size: 13px;
        padding: 8px 6px;
      }
      .suggestions {
        font-size: 13px;
      }
      .total-box {
        font-size: 16px;
        text-align: right;
        margin-top: 10px;
      }
      /* Sticky total bar on small screens */
      .sticky-total {
        position: sticky;
        bottom: 0;
        background: #fff;
        padding: 10px;
        box-shadow: 0 -3px 8px rgba(0,0,0,0.08);
        border-top: 1px solid #ddd;
      }
    }
  </style>
</head>
<body>
   <div class="container-fluid px-2 px-md-4">
    <div class="card p-3 p-md-4">
      <div class="page-title text-center">🛒 Add New Stock</div>


      <form id="orderForm">
       <input type="hidden" id="FrId" name="FrId" value="<?php echo $_GET['user_id'];?>">
         <input type="hidden" id="UserId" name="UserId" value="<?php echo $_GET['user_id'];?>">
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center" id="productTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Rate (₹)</th>
                <th>Quantity</th>
                <th>Total (₹)</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td style="position:relative;">
                  <input type="text" class="form-control product-search" placeholder="Search Product...">
                  <div class="suggestions d-none"></div>
                </td>
                <td><input type="number" class="form-control rate" readonly></td>
                <td><input type="number" class="form-control qty" value="1" min="1"></td>
                <td><input type="number" class="form-control total" readonly></td>
                <td><button class="btn btn-danger btn-sm removeRow">
  <i class="bi bi-trash"></i>
</button></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="text-end mb-3">
          <button type="button" class="btn btn-success btn-sm btn-custom" id="addRow">+ Add Product</button>
        </div>

        <div class="row">
          <div class="col-md-8 text-end total-box">
            Total Amount: ₹<span id="grandTotal">0.00</span>
          </div>
        </div>

        <hr>
        <div class="text-center mt-3">
          <button type="submit" name="submit" class="btn btn-primary btn-custom">Submit Order</button>
          <button type="reset" class="btn btn-secondary btn-custom">Reset</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    // --- Functions ---
    function calculateTotal(row) {
      const rate = parseFloat(row.find('.rate').val()) || 0;
      const qty = parseFloat(row.find('.qty').val()) || 0;
      row.find('.total').val((rate * qty).toFixed(2));
      calculateGrandTotal();
    }

    function calculateGrandTotal() {
      let total = 0;
      $('.total').each(function(){ total += parseFloat($(this).val()) || 0; });
      $('#grandTotal').text(total.toFixed(2));
    }

$(document).on('input focus', '.product-search', function() {
  const input = $(this);
  const suggestionBox = input.next('.suggestions');

  // Reposition suggestion box below input
  const offset = input.offset();
  const height = input.outerHeight();
  suggestionBox.css({
    top: offset.top + height + 2 + 'px',
    left: offset.left + 'px',
    width: input.outerWidth() + 'px'
  });
});

    // --- Product Search ---
    $(document).on('input', '.product-search', function() {
  const input = $(this);
  const query = input.val().trim();
  const suggestionBox = input.next('.suggestions');
  var FrId = $('#FrId').val();
  if (query.length < 2) {
    suggestionBox.addClass('d-none');
    return;
  }

  $.ajax({
    url: 'fetch_products.php',
    method: 'POST',
    data: { query: query,FrId:FrId },
    success: function(data) {
      suggestionBox.html(data).removeClass('d-none');
      input.attr('data-index', '-1'); // reset selection
    }
  });
});

// Keyboard navigation â†“ â†‘ and Enter
$(document).on('keydown', '.product-search', function(e) {
  const input = $(this);
  const suggestionBox = input.next('.suggestions');
  const items = suggestionBox.find('.suggestion-item');
  let index = parseInt(input.attr('data-index') || -1);

  if (suggestionBox.hasClass('d-none')) return;

  if (e.key === 'ArrowDown') {
    e.preventDefault();
    index = (index + 1) % items.length;
    highlight(items, index);
    input.attr('data-index', index);
  } 
  else if (e.key === 'ArrowUp') {
    e.preventDefault();
    index = (index - 1 + items.length) % items.length;
    highlight(items, index);
    input.attr('data-index', index);
  } 
  else if (e.key === 'Enter') {
    e.preventDefault();
    if (index >= 0 && index < items.length) {
      const selected = $(items[index]);
      selectProduct(selected);
      suggestionBox.addClass('d-none');
    }
  }
});

// Highlight function
function highlight(items, index) {
  items.removeClass('bg-light');
  if (index >= 0 && index < items.length) {
    $(items[index]).addClass('bg-light');
    // Auto-scroll into view if overflow
    const parent = $(items[index]).closest('.suggestions');
    const itemTop = $(items[index]).position().top;
    const itemHeight = $(items[index]).outerHeight();
    const parentHeight = parent.height();
    if (itemTop + itemHeight > parentHeight || itemTop < 0) {
      parent.scrollTop(parent.scrollTop() + itemTop);
    }
  }
}

// Click on product
$(document).on('click', '.suggestion-item', function() {
  selectProduct($(this));
});

// Product selection logic
function selectProduct(item) {
  const row = item.closest('tr');
  const input = row.find('.product-search');
  const name = item.data('name');
  const price = item.data('price');
  const mainid = item.data('id');        // from tbl_cust_products_2025.id
  const prodid = item.data('prodid');    // from tbl_cust_products_2025.ProdId

  // Set values into the row
  input.val(name);
  input.attr('data-id', mainid);         // 👈 attach data to the input itself
  input.attr('data-prodid', prodid);
  row.find('.rate').val(price);

  // Hide suggestions and calculate total
  row.find('.suggestions').addClass('d-none');
  calculateTotal(row);
}

    // Select suggestion
    

    // Add row
    $('#addRow').on('click', function() {
  const table = $('#productTable tbody');
  const newRow = table.find('tr:first').clone();

  // Reset input fields
  newRow.find('input').val('');
  newRow.find('.suggestions').addClass('d-none');
  newRow.find('td:first').text(table.find('tr').length + 1);

  // Append the new row
  table.append(newRow);

  // Recalculate totals
  calculateGrandTotal();

  // ðŸŸ¢ Automatically focus on product search of new row
  const newInput = newRow.find('.product-search');
  newInput.focus();

  // Optional: scroll the table if many rows
  $('html, body').animate({
    scrollTop: newInput.offset().top - 120
  }, 200);
});

    // Remove row
    $(document).on('click', '.removeRow', function(){
      $(this).closest('tr').remove();
      calculateGrandTotal();
    });

    // Quantity change
    $(document).on('input', '.qty', function(){
      calculateTotal($(this).closest('tr'));
    });

    // Form submit
   $('#orderForm').on('submit', function(e) {
  e.preventDefault();

  // Collect all product data
  const products = [];
  $('#productTable tbody tr').each(function() {
    const row = $(this);
    const name = row.find('.product-search').val().trim();
    const price = parseFloat(row.find('.rate').val()) || 0;
    const qty = parseFloat(row.find('.qty').val()) || 0;
    const total = parseFloat(row.find('.total').val()) || 0;
    const prodId = row.find('.product-search').data('id') || '';
    const mainProdId = row.find('.product-search').data('prodid') || '';
    if (name !== '' && qty > 0) {
      products.push({
        name: name,
        price: price,
        qty: qty,
        total: total,
        prodId: prodId,
        mainProdId: mainProdId
      });
    }
  });

  if (products.length === 0) {
    alert('⚠️ Please add at least one product before submitting.');
    return;
  }

  $.ajax({
    url: 'save_stock.php',
    type: 'POST',
    data: {
      products: JSON.stringify(products),
      FrId: $('#FrId').val(),      // You can replace with actual session Franchise ID
            // Replace with logged distributor ID
      CreatedBy: $('#UserId').val()  // Replace with session user ID
    },
    success: function(res) {
    if (res.status === 'success') {
      alert('✅ Order submitted successfully!');
      $('#orderForm')[0].reset();
      $('#grandTotal').text('0.00');
      $('#productTable tbody').html($('#productTable tbody tr:first').prop('outerHTML'));
    } else {
      alert('❌ ' + (res.message || 'Something went wrong.'));
    }
  },
  error: function(xhr, status, error) {
    console.error('AJAX Error:', status, error, xhr.responseText);
    alert('⚠️ Failed to connect to the server. Please try again.');
  }
  });
});
  </script>
</body>
</html>
