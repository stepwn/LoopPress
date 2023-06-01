"use strict";

/**
 * Example JavaScript code that interacts with the page and Web3 wallets
 */

// Create a new <script> element
var script = document.createElement('script');

// Set the source attribute to the path of the JavaScript file
script.src = '../wp-content/plugins/LoopPress/assets/bundle.js';

// Append the <script> element to the <head> or <body> of the HTML document
document.head.appendChild(script); // or document.body.appendChild(script);

script.onload = function () {
// Code that depends on the dynamically loaded JavaScript file
const Web3Modal = window.Web3Modal;
var signClient;
//const WalletConnectProvider = window.WalletConnectProvider.default;
const evmChains = window.evmChains;

// Web3modal instance
let web3Modal;
// Rest of your code...


// Chosen wallet provider given by the dialog window
let provider;


// Address of the selected account
let selectedAccount;


// 1. Define constants
//const projectId = "6f7194e0ee25e9dd0e4bf771c230a565";
const projectId = wc_projectId;
const namespaces = {
  eip155: {
    methods: ["eth_sign"],
    chains: ["eip155:1"],
    events: ["accountsChanged"],
  },
};

/**
 * Setup the orchestra
 */
async function init() {

  console.log("Initializing example");
  //console.log("WalletConnectProvider is", WalletConnectProvider);
  console.log("window.web3 is", window.web3, "window.ethereum is", window.ethereum);
		if (window.ethereum) {
  // Request access to the user's accounts
  const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
  
  // The accounts array contains the user's Ethereum addresses
  const address = accounts[0];
  
  console.log('Ethereum address:', address);
  window.walletAddress = address;
} else {
  console.log('Ethereum provider not found');
  // Check that the web page is run in a secure context,
  // as otherwise MetaMask won't be available
  if(location.protocol !== 'https:') {
    // https://ethereum.stackexchange.com/a/62217/620
    const alert = document.querySelector("#alert-error-https");
    alert.style.display = "block";
    document.querySelector("#btn-connect").setAttribute("disabled", "disabled")
    return;
  }
  // Tell Web3modal what providers we have available.
  // Built-in web browser provider (only one can exist as a time)
  // like MetaMask, Brave or Opera is added automatically by Web3modal
  const providerOptions = {
  };
  console.log(Web3Modal);
  web3Modal = new Web3Modal({
    projectId,
	standaloneChains: namespaces.eip155.chains,
  });
  signClient = await SignClient.init({ projectId });

  console.log("Web3Modal instance is", web3Modal);
}
}

async function connectWalletConnect(){
  //console.log("WalletConnectProvider is", WalletConnectProvider);
  console.log("window.web3 is", window.web3, "window.ethereum is", window.ethereum);

  // Check that the web page is run in a secure context,
  // as otherwise MetaMask won't be available
  if(location.protocol !== 'https:') {
    // https://ethereum.stackexchange.com/a/62217/620
    const alert = document.querySelector("#alert-error-https");
    alert.style.display = "block";
    document.querySelector("#btn-connect").setAttribute("disabled", "disabled")
    return;
  }

  // Tell Web3modal what providers we have available.
  // Built-in web browser provider (only one can exist as a time)
  // like MetaMask, Brave or Opera is added automatically by Web3modal
  
	
	

   web3Modal = new Web3Modal({
    projectId,
	standaloneChains: namespaces.eip155.chains,
  });
	signClient = await SignClient.init({ projectId });
  console.log("Web3Modal instance is", web3Modal);
}
function showSpinner(remove=true) {
  if (!remove) {
    // Remove the spinner and overlay elements from the DOM
    document.querySelector('.spinner').remove();
    document.querySelector('.overlay').remove();
    return;
  }

  // Create a new div element for the overlay
  const overlay = document.createElement('div');
  overlay.className = 'overlay';

  // Add the overlay element to the DOM
  document.body.appendChild(overlay);

  // Apply CSS styles to the overlay element
  overlay.style.position = 'fixed';
  overlay.style.top = '0';
  overlay.style.left = '0';
  overlay.style.width = '100%';
  overlay.style.height = '100%';
  overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';

  // Create a new div element for the spinner
  const spinner = document.createElement('div');
  spinner.className = 'spinner';

  // Add the spinner element to the DOM
  document.body.appendChild(spinner);

  // Apply CSS styles to the spinner element
  spinner.style.width = '40px';
  spinner.style.height = '40px';
  spinner.style.borderRadius = '50%';
  spinner.style.border = '3px solid #f3f3f3';
  spinner.style.borderTop = '3px solid #3498db';
  spinner.style.animation = 'spin 0.8s linear infinite';
  spinner.style.position = 'fixed';
  spinner.style.top = '50%';
  spinner.style.left = '50%';
  spinner.style.transform = 'translate(-50%, -50%)';

  // Define the keyframe animation for the spinner
  const keyframes = `
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  `;

  // Add the keyframe animation to the document head
  const style = document.createElement('style');
  style.appendChild(document.createTextNode(keyframes));
  document.head.appendChild(style);
}

/**
 * Kick in the UI action after Web3modal dialog has chosen a provider
 */
async function fetchAccountData() {
  // add a js spinner while this loads
  showSpinner(true);
  //const web3 = new Web3(provider);

  //console.log("Web3 instance is", web3);

  //const chainId = await web3.eth.getChainId();
  //const networkName = getNetworkName(chainId);
  //document.querySelector("#network-name").textContent = networkName;

  const accounts = window.walletAddress;
  console.log("Got accounts", accounts);
  selectedAccount = window.walletAddress;

  document.querySelector("#selected-account").textContent = selectedAccount;

  // Get account balance from Etherscan API
  //const balance = await getAccountBalance(selectedAccount);
  //document.querySelector("#account-balance").textContent = formatBalance(balance);
  // Get account id from loopring API
  const loopringID = await getLoopringAccountID(selectedAccount);
  document.querySelector("#loopring-account-ID").textContent = loopringID;
	const ownedNFTs = await getLoopringNFTs(loopringID);
	/*document.querySelector("#membership").innerHTML ="";
	const memberNFTIds = ['0x76ba5274a8f3e6812a5622cb1342a5aa7bd7ec10', '0x2792d09216d8add860038587c8883e53f1940d65'];
	ownedNFTs.data.forEach(d => {
	  if (memberNFTIds.includes(d.tokenAddress.trim().toLowerCase())) {
		document.querySelector("#membership").innerHTML += d.collectionInfo.name + "<br><img src='" + d.collectionInfo.cached.avatar + "' width='25%'><br>";
	  }
	});*/

  document.querySelector("#prepare").style.display = "none";
  document.querySelector("#connected").style.display = "block";
  document.querySelector("#btn-connect").style.display = "none";
  if(document.querySelector("#btn-connect").getAttribute("redirect")=="1"){
		window.location.reload();
}
showSpinner(false);
}

async function hasMembership(nftAddress) {
  const loopringID = await getLoopringAccountID(selectedAccount);
  const ownedNFTs = await getLoopringNFTs(loopringID);
  
  for (let i = 0; i < ownedNFTs.data.length; i++) {
    const nft = ownedNFTs.data[i];
    if (nft.tokenAddress.trim().toLowerCase() === nftAddress.trim().toLowerCase()) {
      return true;
    }
  }
  
  return false;
}
async function hasMembershipByMinter(minterAddress) {
  const loopringID = await getLoopringAccountID(selectedAccount);
  const ownedNFTs = await getLoopringNFTs(loopringID);

  for (let i = 0; i < ownedNFTs.data.length; i++) {
    const nft = ownedNFTs.data[i];
    if (nft.minter.toLowerCase() === minterAddress.toLowerCase()) {
      return true;
    }
  }

  return false;
}


function getNetworkName(chainId) {
  const chainData = evmChains.getChain(chainId);
  return chainData ? chainData.name : "Unknown network";
}



function formatBalance(balance) {
  return '${Web3.utils.fromWei(balance, "ether")} ETH';
}

async function getLoopringAccountID(account) {
  const headers = {
    'Content-Type': 'application/json',
    'X-API-KEY': '',
    'X-Proxy-Key': looppressKey
  };
  
  const response = await fetch('/wp-content/plugins/LoopPress/proxy.php?url=https://api3.loopring.io/api/v3/account&owner='+account, {
    headers: headers
  });const data = await response.json();
  return data.accountId;
}


async function getLoopringNFTs(account){
  // Ethereum address of the user whose NFT balances to retrieve
  const userAddress = account;
  // API endpoint URL
  const apiUrl ='/wp-content/plugins/LoopPress/proxy.php?url=https://api3.loopring.io/api/v3/user/nft/balances&owner='+account;
  // HTTP headers for the API request
  const headers = {
    'Content-Type': 'application/json',
    'X-API-KEY': '',
    'X-Proxy-Key': looppressKey
  };
  // Make the API request using fetch()
  const NFTlist = await fetch(apiUrl, {
    method: 'GET',
    headers: headers
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response;
  })
  .catch(error => {
    // API request failed, display an error message
    console.error('Error retrieving NFT balances:', error);
  });
  return NFTlist;
}

/** 
 * Fetch account data for UI when
 * - User switches accounts in wallet
 * - User switches networks in wallet
 * - User connects wallet initially
 */
async function refreshAccountData() {

  // If any current data is displayed when
  // the user is switching acounts in the wallet
  // immediate hide this data
  document.querySelector("#connected").style.display = "none";
  document.querySelector("#prepare").style.display = "block";

  // Disable button while UI is loading.
  // fetchAccountData() will take a while as it communicates
  // with Ethereum node via JSON-RPC and loads chain data
  // over an API call.
  document.querySelector("#btn-connect").setAttribute("disabled", "disabled")
  await fetchAccountData(provider);
  document.querySelector("#btn-connect").removeAttribute("disabled")
}


/**
 * Connect wallet button pressed.
 */
async function onConnect() {
	if(localStorage.getItem('wc@2:client:0.3//session') != undefined && localStorage.getItem('wc@2:client:0.3//session')!= "[]"){
		const sessionData = JSON.parse(localStorage.getItem('wc@2:client:0.3//session'));
        const address = sessionData[0].namespaces.eip155.accounts[0];
        const addressParts = address.split(':');
        const walletAddress = addressParts[2];
		window.walletAddress = walletAddress;
		console.log('Wallet address:', address);
	}else{
  console.log("Opening a dialog", web3Modal);
  console.log("signClient, ", signClient);
  if (signClient) {
      const { uri, approval } = await signClient.connect({
        requiredNamespaces: namespaces,
      });
      if (uri) {
        await web3Modal.openModal({ uri });
        const accounts = await approval();
        web3Modal.closeModal();
        console.log(web3Modal);
        console.log(signClient);
        const sessionData = JSON.parse(localStorage.getItem('wc@2:client:0.3//session'));
        const address = sessionData[0].namespaces.eip155.accounts[0];
        const addressParts = address.split(':');
        const walletAddress = addressParts[2];
		window.walletAddress = walletAddress;
        //document.getElementById("connect-button").innerText = walletAddress;
        console.log('Wallet address:', address);

        //console.log("Approved Ethereum address:", signClient.accounts[0]);
      }
    }
	}
  await refreshAccountData();
  
}

/**
 * Disconnect wallet button pressed.
 */

async function onDisconnect() {
  document.cookie = 'PHPSESSID=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	selectedAccount = null;
// Clear the local storage
localStorage.clear();
  clear_session();
  console.log("Killing the wallet connection", provider);

  //await signClient.disconnect();


  selectedAccount = null;

  // Set the UI back to the initial state
  document.querySelector("#prepare").style.display = "block";
  document.querySelector("#btn-connect").style.display = "block";
  document.querySelector("#connected").style.display = "none";
}


/**
 * Main entry point.
 */
window.addEventListener('load', async () => {
  const connectSection = document.querySelector("#connect-wallet-section");
    if (connectSection) {
        connectSection.style.display = "block";
        document.querySelector("#btn-connect").addEventListener("click", async ()=>{await init();onConnect()});
        if(document.querySelector("#btn-connect").getAttribute("isLoggedIn") === "True"){
          await init();
          onConnect();
        }
        // Check if user is on mobile device
      //if (/Mobi/.test(navigator.userAgent)) {
        // Add a button specifically for WalletConnect
        const wcButton = document.createElement('button');
        wcButton.innerHTML = 'Connect with WalletConnect';
        wcButton.classList = 'wp-block-button__link wp-element-button';
        wcButton.style ='display:block;margin:auto;width:fit-content;margin-top:8px;';
        wcButton.onclick = async function() {
          // Call the connect function for WalletConnect
          await connectWalletConnect();
          onConnect();
        }
        document.querySelector("#btn-connect").parentNode.insertBefore(wcButton, document.querySelector("#btn-connect").nextSibling);
      //}
    }
    const dcButton = document.querySelector("#btn-disconnect");
    if(dcButton){
      dcButton.addEventListener("click", onDisconnect);
    }
});
}