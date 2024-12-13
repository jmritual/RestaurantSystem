<?php
class Product extends Database {
    // Fetch all products
    public function getAllProducts() {
        $select_products = $this->getConnection()->prepare("SELECT * FROM `products`");
        $select_products->execute();

        // Return the fetched products or an empty array if no results
        return ($select_products->rowCount() > 0) ? $select_products->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Fetch products by category
    public function getProductsByCategory($category) {
        $select_products = $this->getConnection()->prepare("SELECT * FROM `products` WHERE category = ?");
        $select_products->execute([$category]);

        // Return the fetched products by category or an empty array if no results
        return ($select_products->rowCount() > 0) ? $select_products->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Search products by name
    public function searchProducts($searchTerm) {
        $select_products = $this->getConnection()->prepare("SELECT * FROM `products` WHERE name LIKE ?");
        $select_products->execute(["%{$searchTerm}%"]);

        // Return the fetched products by search term or an empty array if no results
        return ($select_products->rowCount() > 0) ? $select_products->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Fetch cart items by user ID
    public function getCartItemsByUserId($userId) {
        $select_cart = $this->getConnection()->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$userId]);

        // Return the fetched cart items or an empty array if no results
        return ($select_cart->rowCount() > 0) ? $select_cart->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Delete a cart item by cart ID
    public function deleteCartItem($cartId) {
        $delete_cart_item = $this->getConnection()->prepare("DELETE FROM `cart` WHERE id = ?");
        $delete_cart_item->execute([$cartId]);
        return 'Cart item deleted!';
    }

    // Delete all cart items by user ID
    public function deleteAllCartItems($userId) {
        $delete_cart_item = $this->getConnection()->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart_item->execute([$userId]);
        return 'Deleted all from cart!';
    }

    // Update quantity of a cart item by cart ID
    public function updateCartItemQuantity($cartId, $quantity) {
        $update_qty = $this->getConnection()->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        $update_qty->execute([$quantity, $cartId]);
        return 'Cart quantity updated';
    }

    // Place an order
    public function placeOrder($userId, $name, $number, $email, $method, $address, $totalProducts, $totalPrice) {
        // Insert order details into the orders table
        $insert_order = $this->getConnection()->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_order->execute([$userId, $name, $number, $email, $method, $address, $totalProducts, $totalPrice]);

        // Delete items from the cart after placing the order
        $delete_cart = $this->getConnection()->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$userId]);

        return 'Order placed successfully!';
    }

    // Get user orders by user ID
    public function getUserOrders($userId) {
        $select_orders = $this->getConnection()->prepare("SELECT * FROM `orders` WHERE user_id = ?");
        $select_orders->execute([$userId]);

        $orders = [];

        if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                $orders[] = $fetch_orders;
            }
        }

        return $orders;
    }
}
?>