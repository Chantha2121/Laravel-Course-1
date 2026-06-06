<?php

class Product {
    public function __construct(
        private int $id,
        private string $name,
        private float $price
    ) {}

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
}

class CartItem {
    public function __construct(
        private Product $product,
        private int $quantity
    ) {}

    public function getProduct(): Product { return $this->product; }
    public function getQuantity(): int { return $this->quantity; }
    
    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function getTotalPrice(): float {
        return $this->product->getPrice() * $this->quantity;
    }
}

class ShoppingCart {
    private array $items = []; // Stores CartItem objects

    public function addProduct(Product $product, int $quantity): void {
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Quantity must be greater than 0.");
        }

        // If product already exists in cart, just update the quantity
        if (isset($this->items[$product->getId()])) {
            $currentQty = $this->items[$product->getId()]->getQuantity();
            $this->items[$product->getId()]->setQuantity($currentQty + $quantity);
            return;
        }

        // Otherwise, add it as a new item
        $this->items[$product->getId()] = new CartItem($product, $quantity);
    }

    public function removeProduct(int $productId): void {
        unset($this->items[$productId]);
    }

    public function getTotal(): float {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->getTotalPrice();
        }
        return $total;
    }
}

// --- Usage Example ---
$cart = new ShoppingCart();
$laptop = new Product(1, "PHP Rocket Laptop", 999.99);
$mouse = new Product(2, "Wireless Mouse", 49.50);

$cart->addProduct($laptop, 1);
$cart->addProduct($mouse, 2);

echo "Total Cart Value: $" . $cart->getTotal() . "\n"; // Output: 1098.99