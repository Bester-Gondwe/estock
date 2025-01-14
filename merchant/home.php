  <?php
    require_once '../models/Order.php';
    $order = new Order();

    // get the first 8 recent orders
    $recentOrders = $order->getOrdersPaginated($_SESSION['user_id'], 0, 8);
    ?>

  <!-- Merchant Dashboard Overview -->
  <div class="dashboard-overview">
      <div class="overview-card">
          <h3>Total Products</h3>
          <p><?php echo $order->countProductsForMerchant($_SESSION['user_id']) ?></p>
      </div>
      <div class="overview-card">
          <h3>Total Orders</h3>
          <p><?php echo $order->countOrderForMerchant($_SESSION['user_id']); ?></p>
      </div>
      <div class="overview-card">
          <h3>Pending Orders</h3>
          <p><?php echo $order->countOrderByStatusForMerchant($_SESSION['user_id'], 'Pending') ?></p>
      </div>
      <div class="overview-card">
          <h3>Completed Orders</h3>
          <p><?php echo $order->countOrderByStatusForMerchant($_SESSION['user_id'], 'Derivered') ?></p>
      </div>
  </div>

  <section class="orders">
      <div class="orders-table__wrapper">
          <div>
              <h4 class="table-title">Recent Purchases</h4>
          </div>
          <table class="orders-table">
              <thead>
                  <tr>
                      <th>Product</th>
                      <th>Order ID</th>
                      <th>Date</th>
                      <th>Customer Name</th>
                      <th>Status</th>
                      <th>Amount</th>
                  </tr>
              <tbody>

                  <?php
                    foreach ($order->getOrdersPaginated($_SESSION['user_id'], 0, 8) as $recentOrder) {
                        $date = new DateTime($recentOrder['order_date']);
                        $formattedDate = $date->format('M jS, Y'); // Format the date
                        echo "<tr class='order-table__row' id='row-1' data-id='{$recentOrder['order_id']}'>
                         <td>{$recentOrder['product_name']}</td>
                         <td>#{$recentOrder['order_id']}</td>
                         <td>{$formattedDate}</td>
                         <td>{$recentOrder['customer_name']}</td>
                         <td><span class='dot derivered'></span>{$recentOrder['order_status']}</td>
                         <td>MWK200000</td> </tr>";
                    } ?>
              </tbody>
              </thead>
          </table>
      </div>
  </section>

  <script>
      const ordersRows = document.querySelectorAll(".order-table__row");
      ordersRows.forEach((order, _) => {
          order.addEventListener('click', function(e) {
              e.preventDefault();
              window.location.href = `index.php?p=order_details&orderID=${this.dataset.id}`
          })
      })
  </script>