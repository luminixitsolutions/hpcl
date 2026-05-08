<?php
header('Content-Type: text/html; charset=utf-8');
include '../config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Request Order - Distributor Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
  /* ==== Base Styling ==== */
  body {
    background-color: #f3f1f8;
    font-family: "Inter", "Segoe UI", sans-serif;
    color: #333;
    padding: 15px;
  }

  .card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 3px 15px rgba(112, 79, 255, 0.08);
    border: none;
    padding: 25px;
  }

  .page-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: #5b3cc4;
    text-align: center;
    margin-bottom: 25px;
  }

  /* ==== Buttons ==== */
  .btn-custom {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
  }

  .btn-primary {
    background-color: #6a4fe0 !important;
  }

  .btn-primary:hover {
    background-color: #5a41c6 !important;
  }

  .btn-success {
    background-color: #00b894 !important;
  }

  .btn-success:hover {
    background-color: #00a184 !important;
  }

  .btn-secondary {
    background-color: #b2bec3 !important;
  }

  .btn-secondary:hover {
    background-color: #979da0 !important;
  }

  .btn-danger {
    background-color: #ff7675 !important;
  }

  .btn-danger:hover {
    background-color: #e15e5e !important;
  }

  /* ==== Table Styling ==== */
  .table thead {
    background-color: #6a4fe0;
    color: #fff;
    font-weight: 600;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  .table thead th {
    border: none;
    vertical-align: middle;
    text-align: center;
    font-size: 14px;
    padding: 12px;
  }

  .table tbody td {
    background-color: #fdfdff;
    border-color: #e8e5f2;
    vertical-align: middle;
    text-align: center;
    font-size: 14px;
  }

  .table tbody tr:hover {
    background-color: #f5f2ff;
    transition: 0.2s ease-in;
  }

  .table-bordered {
    border-color: #e8e5f2;
  }

  /* ==== Inputs ==== */
  .form-control {
    border-radius: 8px;
    border: 1px solid #d5cfff;
    box-shadow: none;
    font-size: 14px;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: #7c5bff;
    box-shadow: 0 0 0 0.15rem rgba(124, 91, 255, 0.2);
  }

  /* ==== Total Section ==== */
  .total-box {
    font-size: 1.1rem;
    font-weight: 700;
    color: #3fb984;
    text-align: right;
  }

  .sticky-total {
    position: sticky;
    bottom: 0;
    background: #ffffff;
    padding: 10px;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    border-top: 1px solid #e8e5f2;
  }

  /* ==== Suggestion Box ==== */
  .suggestions {
    position: fixed !important;
    background: #fff;
    border: 1px solid #d9d2ff;
    z-index: 9999 !important;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(95, 58, 255, 0.1);
    max-height: 220px;
    overflow-y: auto;
  }

  .suggestion-item {
    padding: 10px 14px;
    cursor: pointer;
    transition: background 0.25s;
  }

  .suggestion-item:hover,
  .suggestion-item.bg-light {
    background-color: #f3edff;
  }

  /* ==== Mobile Optimization ==== */
  @media (max-width: 768px) {
    .page-title {
      font-size: 1.3rem;
    }
    .table th,
    .table td {
      font-size: 13px;
      padding: 6px;
    }
    .btn-custom {
      font-size: 13px;
      padding: 8px 16px;
    }
    .sticky-total {
      text-align: center;
    }
  }
</style>

</head>
<body>
  <div class="container-fluid px-2 px-md-4">
    <div class="card p-3 p-md-4">
      <div class="page-title text-center">🛒 Request Order</div>

      <form id="orderForm">
        <input type="hidden" id="FrId" name="FrId" value="<?php echo $_GET['user_id']; ?>">
        <input type="hidden" id="UserId" name="UserId" value="<?php echo $_GET['user_id']; ?>">

        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center" id="productTable">
            <thead>
              <tr>
                <th>#</th>
                <th>Product</th>
                <th>Rate (₹)</th>
                <th>Qty</th>
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
                <td>
                  <button class="btn btn-danger btn-sm removeRow">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="text-end mb-3 mt-2">
          <button type="button" class="btn btn-success btn-sm btn-custom" id="addRow">
            + Add Product
          </button>
        </div>

        <div class="row sticky-total">
          <div class="col-md-8 text-end total-box">
            Total Amount: ₹<span id="grandTotal">0.00</span>
          </div>
        </div>

        <hr>
        <div class="text-center mt-3">
          <button type="submit" name="submit" class="btn btn-primary btn-custom w-100 w-md-auto mb-2">
            Submit Order
          </button>
          <button type="reset" class="btn btn-secondary btn-custom w-100 w-md-auto">
            Reset
          </button>
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
      $('.total').each(function() { total += parseFloat($(this).val()) || 0; });
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
        data: { query: query, FrId: FrId },
        success: function(data) {
          suggestionBox.html(data).removeClass('d-none');
          input.attr('data-index', '-1');
        }
      });
    });

    // Keyboard navigation ↓ ↑ and Enter
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

    function highlight(items, index) {
      items.removeClass('bg-light');
      if (index >= 0 && index < items.length) {
        $(items[index]).addClass('bg-light');
        const parent = $(items[index]).closest('.suggestions');
        const itemTop = $(items[index]).position().top;
        const itemHeight = $(items[index]).outerHeight();
        const parentHeight = parent.height();
        if (itemTop + itemHeight > parentHeight || itemTop < 0) {
          parent.scrollTop(parent.scrollTop() + itemTop);
        }
      }
    }

    $(document).on('click', '.suggestion-item', function() {
      selectProduct($(this));
    });

    function selectProduct(item) {
      const row = item.closest('tr');
      const input = row.find('.product-search');
      const name = item.data('name');
      const price = item.data('price');
      const mainid = item.data('id');
      const prodid = item.data('prodid');

      input.val(name);
      input.attr('data-id', mainid);
      input.attr('data-prodid', prodid);
      row.find('.rate').val(price);

      row.find('.suggestions').addClass('d-none');
      calculateTotal(row);
    }

    // Add Row
    $('#addRow').on('click', function() {
      const table = $('#productTable tbody');
      const newRow = table.find('tr:first').clone();
      newRow.find('input').val('');
      newRow.find('.suggestions').addClass('d-none');
      newRow.find('td:first').text(table.find('tr').length + 1);
      table.append(newRow);
      calculateGrandTotal();
      const newInput = newRow.find('.product-search');
      newInput.focus();
      $('html, body').animate({ scrollTop: newInput.offset().top - 120 }, 200);
    });

    // Remove Row
    $(document).on('click', '.removeRow', function(e){
      e.preventDefault();
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
          products.push({ name, price, qty, total, prodId, mainProdId });
        }
      });

      if (products.length === 0) {
        alert('⚠️ Please add at least one product before submitting.');
        return;
      }

      $.ajax({
        url: 'save_request_order.php',
        type: 'POST',
        dataType: 'json',
        data: {
          products: JSON.stringify(products),
          FrId: $('#FrId').val(),
          CreatedBy: $('#UserId').val()
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
