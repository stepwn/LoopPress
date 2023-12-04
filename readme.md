Contributors: Stephen@swantech.us

Tags: nft, authentication, content protection

Requires at least: 5.0

Tested up to: 6.3.1

Stable tag: 1.0.4

PHP: 7.4

License: GPL-3.0

License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Discord: https://discord.gg/gqwrFkwcGg

Website: https://looppress.dev


<h1>LoopPress</h1>
<figure>
  <img src="https://github.com/stepwn/LoopPress/assets/7231316/3e81795c-d8f6-4450-babd-4bdc35541f45" alt="Token Gated Content">
  <figcaption>Token Gated Content</figcaption>
</figure>


<hr>
LoopPress is a powerful and easy-to-use WordPress plugin that enables users to gate content based on NFT ownership. With LoopPress, you can easily protect your content and offer exclusive access to users who own a specific NFT. LoopPress uses PHP and JS to run standalone on your WordPress site's server. (That means its free to use!)


<h2>Features</h2>

☑️ Protect content with NFT ownership verification based on contract, minter, or NFT ID

☑️ Assign user roles based on NFT ownership

☑️ Extremely versatile and powerful [looppress][/looppress] , [looppress_button] , and [looppress_media] shortcodes

☑️ Stream protected media with [looppress_media] shortcode

☑️ Supports WalletConnect for secure authentication

☑️ User NFTs are Cached to the session and can be used for any other purpose through JS or PHP

☑️ Easy installation and configuration

☑️ BuddyPress Integration

☑️ WooCommerce Integration

☑️ Safety First!

☑️ <a href="https://www.looppress.dev/support-looppress-development/">FREE!</a>

Quick Example Video:
[Example Video](https://github.com/stepwn/LoopPress/assets/7231316/0862ae86-2d1e-44a6-8ca9-2739e5d01dca)



<h2>Installation</h2>

![image](https://github.com/stepwn/LoopPress/assets/7231316/c0d0a594-b735-4ff5-9daa-30ec8bc6b799)

<hr>
 To install LoopPress, simply follow these steps:
<ul>
<li>Download <a href="https://github.com/stepwn/LoopPress/blob/main/LoopPress.zip">LoopPress.zip</a>
<br><b>*If you clone the repo, rename the inner folder to "LoopPress.zip"</b>

<li>Upload the plugin to your WordPress site

<li>Activate the plugin in your WordPress dashboard

<li>Navigate to the Looppress settings page in the dashboard
</ul>

<h2>Configuration</h2> 

After installing LoopPress, you'll need to configure the plugin. To configure LoopPress, follow these steps:
<ol>
<li>(Reccommended) Create a fresh Loopring account at <a href="https://loopring.io">https://loopring.io</a>
<li>Export your Loopring API key -> https://github.com/stepwn/LoopPress/assets/7231316/00dad3da-61f8-49de-bfed-2f10adbf918d
<br> Look for "api key" in the exported account.
  
<li>Add your API key to the "LoopPress Settings" on the main sidebar of the WordPress admin dashboard

<li>Obtain a ProjectId from https://cloud.walletconnect.com and save it in the LoopPress settings page.
</ol>
If you need any help with configuration, don't hesitate to reach out for support. https://discord.gg/gqwrFkwcGg


## Usage

![Shortcode](https://github.com/stepwn/LoopPress/assets/7231316/64641e8c-ec30-4f03-8cbd-6709e1bf277c)
*eg Shortcode placement in the WordPress Page Editor*

---

![LoopPress Post Type](https://github.com/stepwn/LoopPress/assets/7231316/77e742d0-4837-4f8e-89c8-bbe43ecdcd20)
*LoopPress Post Type*


Using LoopPress is easy. Here are the basic steps to get started:
<ol>
<li>Create a new post or page in WordPress

<li>Use the [looppress contract="tokencollectioncontract" minter="minterethaddress" nft="individualNFTID"] shortcode to protect content based on NFT ownership

<li>Customize the shortcode attributes to fit your requirements (contract or minter or nft is required)

<li>Put your token gated content on the page using the WordPress content editor

<li>At the end of your gated content put the [/looppress] shortcode tag.

<li>Save and publish!
</ol>
For more detailed instructions and examples, check out our documentation.


<h2>Support</h2> 

If you encounter any issues with LoopPress, please don't hesitate to reach out for support. You can find help in the following ways:

Visit our discord https://discord.gg/gqwrFkwcGg

We're here to help, so don't hesitate to reach out if you need assistance.

<h2>Frequently asked questions</h2> 

Q: How does LoopPress verify NFT ownership? 

A: LoopPress uses the Loopring Rest API to authenticate users and verify NFT ownership once the browser or WalletConnect finishes Public Key Verification.

Q: How Safe is this plugin? 

A: LoopPress sets up a local proxy for Loopring API requests so your key is never exposed to the client. Additionally, users are only signing a verification request to prove they own the Eth address they submit, LoopPress can not transfer tokens.

Q: How can I give away NFTs?

A: Loopring has a NFT red packet system. you can put the red packet behind a token gate (or not!)

Q: I have no idea whats happening

A: <a href="https://github.com/stepwn/LoopPress/blob/main/user-onboarding-guide.md">User Onboarding Guide</a>


<h2>Summary</h2> 

LoopPress is the ultimate WordPress plugin for gating content based on NFT ownership. With its powerful features and easy-to-use interface, you can protect your content and offer exclusive access to your audience. Download LoopPress today and start gating your content based on NFT ownership!
