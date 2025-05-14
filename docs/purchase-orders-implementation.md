# Purchase Orders Implementation Plan

## Overview
The Purchase Orders system will be an admin-only feature that manages inventory procurement through suppliers. This document outlines the implementation plan including database structure, features, and process flows.

## Core Components

### 1. Supplier Management (Admin Only)
```
Table: suppliers
- id: bigint
- name: string
- email: string
- phone: string
- address: text
- timestamps
```

### 2. Purchase Orders (Admin Only)
```
Table: purchase_orders
- id: bigint
- supplier_id: foreign key
- po_number: string (unique)
- date: datetime
- status: enum ('draft', 'pending', 'partially_received', 'completed')
- total_amount: decimal
- notes: text
- timestamps
```

```
Table: purchase_order_items
- id: bigint
- purchase_order_id: foreign key
- product_id: foreign key
- quantity: integer
- unit_price: decimal
- subtotal: decimal
```

## Process Flow

1. Create Purchase Order
   - Admin selects supplier
   - System generates PO number
   - Admin adds products with quantities and prices

2. Submit Purchase Order
   - Status changes from 'draft' to 'pending'
   - PO is now ready for receiving items

3. Receive Items
   - Admin can receive items partially or completely
   - Stock levels are updated automatically
   - Status updates to 'partially_received' or 'completed'

4. Complete Purchase Order
   - All items received
   - Final stock update
   - Status changes to 'completed'

## Permissions (Admin Only)

New permissions to be added:
- view suppliers
- create suppliers
- edit suppliers
- delete suppliers
- view purchase orders
- create purchase orders
- edit purchase orders
- receive purchase orders

## Integration Points

1. Product Inventory
   - Stock levels update on item receipt
   - Inventory history tracking

2. Admin Dashboard
   - Recent purchase orders widget
   - Pending receipts overview
   - Low stock alerts

## UI Components

1. Supplier Management
   - List view with search and filters
   - Create/Edit forms
   - Basic supplier details

2. Purchase Orders
   - List view with status filters
   - Create form with dynamic line items
   - View details with receive items functionality
   - Print PO feature

## Data Validation Rules

1. Suppliers
   - Name: required, unique
   - Email: required, valid email format
   - Phone: required, valid format

2. Purchase Orders
   - Supplier: required
   - Items: at least one required
   - Quantities: positive integers
   - Prices: positive decimals

## Next Steps

1. Database Implementation
   - Create migrations
   - Set up model relationships
   - Add required indexes

2. Feature Implementation
   - Create Filament resources
   - Implement business logic
   - Set up permissions
   - Create policies

3. Testing
   - Unit tests for models
   - Feature tests for admin workflows
   - Permission tests