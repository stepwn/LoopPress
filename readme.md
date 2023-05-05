Contributors: Stephen@swantech.us

Tags: nft, authentication, content protection

Requires at least: 5.0

Tested up to: 6.2

Stable tag: 1.0.4

License: GPL-3.0

License URI: https://www.gnu.org/licenses/gpl-3.0.en.html


<h1>LoopPress</h1>
<img src='https://user-images.githubusercontent.com/7231316/236065275-e454e339-8a3a-404b-a9f8-96620b6869b0.PNG'>

LoopPress is a powerful and easy-to-use WordPress plugin that enables users to gate content based on NFT ownership. With LoopPress, you can easily protect your content and offer exclusive access to users who own a specific NFT. LoopPress uses PHP and JS to run directly on your WordPress site's server.


<h2>Features</h2>
<img src='https://user-images.githubusercontent.com/7231316/235256334-7758a036-16b1-4199-94b6-8ab945335a51.png'>

Protect content with NFT ownership verification based on token or minter address

Supports WalletConnect for secure authentication

Easy installation and configuration

Safety First!



<h2>Installation</h2>

 To install LoopPress, simply follow these steps:
<ul>
<li>Download the plugin from the WordPress Plugin Repository or download <a href="https://github.com/stepwn/LoopPress/blob/main/LoopPress.zip">LoopPress.zip</a>
<br><b>*If you download from github, rename the folder to "LoopPress.zip"</b>

<li>Upload the plugin to your WordPress site

<li>Activate the plugin in your WordPress dashboard

<li>Configure the plugin settings according to your requirements
</ul>

<h2>Configuration</h2> 

After installing LoopPress, you'll need to configure the plugin according to your requirements or options. To configure LoopPress, follow these steps:
<ul>
<li>Export your Loopring API key -> <a href="https://docs.loopring.io/en/basics/key_mgmt.html">Loopring Docs</a>

<li>Add your API key to the "LoopPress Settings" on the main sidebar of the WordPress admin dashboard

<li>(optional) Edit the new "LoopPress" Web3 dashboard page to your liking. *leave the [looppress] shortcode in the page editor!

If you need any help with configuration, don't hesitate to contact us for support.


<h2>Usage</h2> 
<img src='https://user-images.githubusercontent.com/7231316/235256376-74517595-6721-4087-919b-c793e913d625.png'>
eg Shortcode placement in the Wordpress Page Editor

Using LoopPress is easy. Here are the basic steps to get started:
<ol>
<li>Create a new post or page in WordPress

<li>Use the [looppress_required token="tokencollectioncontract" minter="minterethaddress" nft_id="individualNFTID"] shortcode to protect content based on NFT ownership

<li>Customize the shortcode attributes to fit your requirements (token or minter or nft_id is required)
Multiples are allowed per shortcode separated by commas with <b>no spaces</b> 

[looppress_required nft_id="nftid1,nftid2,nftid3"]

<li>Put your token gated content on the page using the WordPress content editor

<li>At the end of your gated content put the [/looppress_required] shortcode tag.

<li>Save and publish!

For more detailed instructions and examples, check out our documentation.


<h2>Support</h2> 

If you encounter any issues with LoopPress, please don't hesitate to reach out for support. You can find help in the following ways:

Visit our support page or forum

Contact us via email or social media

We're here to help, so don't hesitate to reach out if you need assistance.

<h2>Frequently asked questions</h2> 

Q: How does LoopPress verify NFT ownership? 

A: LoopPress uses the Loopring Rest API to authenticate users and verify NFT ownership once the browser or WalletConnect finishes Public Key Verification.

Q: How Safe is this plugin? 

A: LoopPress sets up a local proxy for Loopring API requests so your key is never exposed to the client. Additionally, users are only signing a verification request to prove they own the Eth address they submit, LoopPress can not transfer tokens.

Q: How can I give away NFTs?

A: Loopring has a NFT red packet system. you can put the red packet behind a token gate (or not!)


<h2>Summary</h2> 

LoopPress is the ultimate WordPress plugin for gating content based on NFT ownership. With its powerful features and easy-to-use interface, you can protect your content and offer exclusive access to your audience. Download LoopPress today from the WordPress Plugin Repository and start gating your content based on NFT ownership.
