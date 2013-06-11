<?php 
/* ====================
[BEGIN_COT_EXT]
Code=invite
Name=Invite
Category=
Description=A basic invitation and referral system allowing registered users to promote your site.
Version=1.1
Date=2013-june-11
Author=tyler@xaez.org
Copyright=
Notes=
SQL=
Auth_guests=
Lock_guests=RW12345A
Auth_members=RW
Lock_members=12345
Requires_modules=users
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
title=01:string::Invitation from {user_email}
message=02:text::{user_email} has invited you to {site_title}. Click the link below to join.
limit_invite=03:select:5,10,15,20,25,30,40,50,60,70,80,90,100,150,200,250,300,350,400,450,500:30:
limit_expire=04:callback:invite_get_expirations():600:
disable=05:radio::0:
invite_expire=06:callback:invite_get_invite_expire():0
max_user_pair=07:select:0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,55,60,65,70,75,80,85,90,95,100,110:0:
[END_COT_EXT_CONFIG]
==================== */
