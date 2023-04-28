Contributors: Stephen@swantech.us

Tags: nft, authentication, content protection

Requires at least: 5.0

Tested up to: 6.2

Stable tag: 1.0.0

License: GPL-3.0

License URI: https://www.gnu.org/licenses/gpl-3.0.en.html


<h1>LoopPress</h1>

LoopPress is a powerful and easy-to-use WordPress plugin that enables users to gate content based on NFT ownership. With LoopPress, you can easily protect your content and offer exclusive access to users who own a specific NFT. LoopPress uses PHP and JS to run directly on your WordPress site's server.

you can also read this information, with pictures, here https://swantech.us/looppress/

<h2>Features</h2>

Protect content with NFT ownership verification

Supports WalletConnect for secure authentication

Easy installation and configuration

Safety First!



<h2>Installation</h2>

 To install LoopPress, simply follow these steps:

Download the plugin from the WordPress Plugin Repository or download LoopPress.zip 

Upload the plugin to your WordPress site

Activate the plugin in your WordPress dashboard

Configure the plugin settings according to your requirements


<h2>Configuration</h2> 

After installing LoopPress, you'll need to configure the plugin according to your requirements or options. To configure LoopPress, follow these steps:

Export your Loopring API key -> Loopring Docs

Add your API key to the "LoopPress Settings" on the main sidebar of the WordPress admin dashboard

(optional) Edit the new "LoopPress" Web3 dashboard page to your liking. *leave the [looppress] shortcode in the page editor!

If you need any help with configuration, don't hesitate to contact us for support.


<h2>Usage</h2> 

eg Shortcode placement in the Wordpress Page Editor

Using LoopPress is easy. Here are the basic steps to get started:

Create a new post or page in WordPress

Use the [looppress_required token="tokenid1234" minter="minterethaddress"] shortcode to protect content based on NFT ownership

Customize the shortcode attributes to fit your requirements (token or minter is required)

Put your token gated content on the page using the WordPress content editor

at the end of your gated content put the [/looppress_required] shortcode tag.

Save and publish!

For more detailed instructions and examples, check out our documentation.


<h2>Support</h2> 

If you encounter any issues with LoopPress, please don't hesitate to reach out for support. You can find help in the following ways:

Visit our support page or forum

Contact us via email or social media

We're here to help, so don't hesitate to reach out if you need assistance.

<h3>Frequently asked questions</h3> 

Q: How does LoopPress verify NFT ownership? 

A: LoopPress uses the Loopring Rest API to authenticate users and verify NFT ownership once the browser or WalletConnect finishes Public Key Verification.

Q: How Safe is this plugin? 

A: LoopPress sets up a local proxy for Loopring API requests so your key is never exposed to the client. Additionally, users are only signing a verification request to prove they own the Eth address they submit, LoopPress can not transfer tokens.

Q: How can I give away NFTs?

A: Loopring has a NFT red packet system. you can put the red packet behind a token gate (or not!)


Summary 

LoopPress is the ultimate WordPress plugin for gating content based on NFT ownership. With its powerful features and easy-to-use interface, you can protect your content and offer exclusive access to your audience. Download LoopPress today from the WordPress Plugin Repository and start gating your content based on NFT ownership.
