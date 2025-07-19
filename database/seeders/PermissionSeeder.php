<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
    // User
    'create-user',
    'update-user',
    'delete-user',
    'list-user',
    'user-save-page',

    // Yarn Party
    'yarn-party-list',
    'yarn-party-save-page',
    'create-yarn-party',
    'update-yarn-party',
    'yarn-party-delete',
    'yarn-party-detail-list',

    // Yarn Purchase
    'yarn-purchase-list',
    'yarn-purchase-save-page',
    'create-yarn-purchase',
    'update-yarn-purchase',
    'yarn-purchase-delete',

    // Yarn Sale
    'yarn-sale-list',
    'yarn-sale-page',
    'create-yarn-sale',

    // Yarn Payment
    'save-yarn-payment',
    'yarn-payment-list',

    // Knitting Party
    'knitting-party-list',
    'knitting-party-save-page',
    'create-knitting-party',
    'update-knitting-party',
    'knitting-party-delete',
    'knitting-party-detail-list',

    // Knitting
    'knitting-list',
    'knitting-save-page',
    'create-knitting',

    // Knitting Receive
    'knitting-receive-page',
    'create-knitting-receive',
    'knitting-receive-list',

    // Knitting Sale
    'knitting-sale-page',
    'create-knitting-sale',
    'knitting-sale-list',

    // Knitting Payment
    'save-knitting-payment',
    'knitting-payment-list',

    // Dyeing Party
    'dyeing-party-list',
    'dyeing-party-save-page',
    'create-dyeing-party',
    'update-dyeing-party',
    'dyeing-party-delete',
    'dyeing-party-detail-list',

    // Dyeing
    'dyeing-list',
    'dyeing-save-page',
    'create-dyeing',
    'dyeing-receive-page',
    'create-dyeing-receive',

    // Dyeing Payment
    'save-dyeing-payment',
    'dyeing-payment-list',

    // Fabric
    'fabric-list',
    'fabric-sale-page',
    'fabric-sale',
    'fabric-sale-list',

    // Category
    'list-category',
    'category-save-page',
    'create-category',
    'update-category',
    'delete-category',

    // Cutting Party
    'cutting-party-list',
    'cutting-party-save-page',
    'create-cutting-party',
    'update-cutting-party',
    'cutting-party-delete',
    'cutting-party-detail-list',

    // Cuttings
    'cutting-list',
    'cutting-save-page',
    'create-cutting',
    'cutting-receive-list',
    'cutting-receive-page',
    'create-cutting-receive',


    // Cutting Payment
    'save-cutting-payment',
    'cutting-payment-list',

    // Sewing Party
    'sewing-party-list',
    'sewing-party-save-page',
    'create-sewing-party',
    'update-sewing-party',
    'sewing-party-delete',
    'sewing-party-detail-list',

    // Sewing
    'sewing-list',
    'sewing-save-page',
    'create-sewing',
    'sewing-receive-list',
    'sewing-receive-page',
    'create-sewing-receive',

    // Sewing Payment
    'save-sewing-payment',
    'sewing-payment-list',

    // Products
    'product-list',
    'product-update-page',
    'update-product',

    // Sale Page
    'sale-page',

    // Customers
    'customer-list',
    'customer-save-page',
    'create-customer',
    'update-customer',
    'delete-customer',
    'customer-payment-list',
    'save-customer-payment',

    // Invoices
    'invoice-list',
    'create-invoice',
    'delete-invoice',

    //roles
    'list-role',
    'role-save-page',
    'create-role',
    'update-role',
    'delete-role',

    //dropdown
    'yarn-dropdown',
    'knitting-dropdown',
    'dyeing-dropdown',
    'cutting-dropdown',
    'sewing-dropdown',
    'fabric-dropdown',
];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }


    }
}
