//
// Dynamsoft JavaScript Library for Basic Initiation of Dynamic Web TWAIN
// More info on DWT: http://www.dynamsoft.com/Products/WebTWAIN_Overview.aspx
//
// Copyright 2017, Dynamsoft Corporation 
// Author: Dynamsoft Team
// Version: 13.2
//
/// <reference path="dynamsoft.webtwain.initiate.js" />
var Dynamsoft = Dynamsoft || { WebTwainEnv: {} };

Dynamsoft.WebTwainEnv.AutoLoad = true;

///
Dynamsoft.WebTwainEnv.Containers = [{ContainerId:'dwtcontrolContainer', Width:270, Height:350}];

/// If you need to use multiple keys on the same server, you can combine keys and write like this 
/// Dynamsoft.WebTwainEnv.ProductKey = 'key1;key2;key3';
Dynamsoft.WebTwainEnv.ProductKey = 'f0068NQAAACibkqYRIkTp/6FAbJ+67i13Hh47sndwybdevLtinkC2FKmrNIdeD62S8dX7SK80aqWZI1Jt0aVSfNJvf1llnn4=;A7CE9618B99C4BB5E5BBE344949286544E0B8CBD357505E84CEAC909B945F83DA41D67CA28B769593719CD021AC4E9DA3692CDAB76195443BC2744840BC10F13A7C7B0A4DACDB0E2019D3ECCAFE3656E72C5E6360EB2B9EB5C3EFBE433B864E721CCAD9DDC596D693652AB3F5221A1228F';

///
Dynamsoft.WebTwainEnv.Trial = false;

///
Dynamsoft.WebTwainEnv.ActiveXInstallWithCAB = false;

///
Dynamsoft.WebTwainEnv.ResourcesPath = '/vendor/DynamicWebTWAINv13.2/Resources';

/// All callbacks are defined in the dynamsoft.webtwain.install.js file, you can customize them.
// Dynamsoft.WebTwainEnv.RegisterEvent('OnWebTwainReady', function(){
// 		// webtwain has been inited
// });

