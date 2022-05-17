# Magento 2 Modules

## GatePay
Adds new payment method to checkout page
![image](https://user-images.githubusercontent.com/91739397/168874346-38fce013-09e5-40e8-896a-a9c649d92c0e.png)

Module configuration page
![image](https://user-images.githubusercontent.com/91739397/168874857-d9273801-e07e-4112-9d2d-81035051fba9.png)

**Currently module requires payu store automatic capture to be enabled.**

After successful order user is redirected to payu payment page

![image](https://user-images.githubusercontent.com/91739397/168875571-fb73f522-d4f0-407b-a361-cf498fa90f37.png)

After confirmation is send from payu order is invoiced. If payment is canceled, authorization is voided and order is cancelled.

Successful transaction

![image](https://user-images.githubusercontent.com/91739397/168877824-953dc717-1222-46a5-9d47-6fd9609b92c1.png)

Canceled payment

![image](https://user-images.githubusercontent.com/91739397/168877658-7f10f590-579e-49a7-b676-0b318fdb09d0.png)


## GateCheckout
Displays short information above the **subtotal** fields in minicart card and also on the checkout cart page.

![Screenshot](scr/GateCheckout_1.png)

![Screenshot](scr/GateCheckout_2.png)

## GateGuest
Very simple guest book implementation. Saves information provided in the form available on the store page, saves it to database after simple validation. Form can be accessed from the link in the footer of the page.

![Screenshot](scr/GateCheckout_3.png)

## GateType
Creates new product type called Virtual Grouped.

![Screenshot](scr/GateCheckout_4.png)

## CRON SCRIPT
Located with sample output file in cronscript directory. Script needs credentials for magento database, configuration requires mysql server address, user name, password and database name. Depending on your server configuration you might need to output csv file to a specific location or change secure-file-priv option in your mysql server configuration for script to work properly

Add this script to your crontab file (crontab -e) : 0 3 * * *  path_to_the _script. This will run this cron job every day at 3 am.

## CheckoutStep
Creates option in Stores->Configuration->Sales->Checkout Allowing to enable or disable billing address form in checkout.
If billing address form is disabled billing address is always the same as shipping address.

![Screenshot](scr/GateCheckout_5.png)
![Screenshot](scr/GateCheckout_6.png)
