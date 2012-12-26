<?php 
ob_start ("ob_gzhandler");
header("Content-type: text/css; charset: UTF-8");
header("Cache-Control: must-revalidate");
$offset = 60 * 60 ;
$ExpStr = "Expires: " . 
gmdate("D, d M Y H:i:s",
time() + $offset) . " GMT";
header($ExpStr);
                ?>

/*** pagination.css ***/

/* -- buttons ->- Borrowed and modified from Khepri admin template */

.jevbutton-left,
.jevbutton-right,
.jevbutton-left div,
.jevbutton-right div {
	float: left;
}

.jevbutton-left a,
.jevbutton-right a,
.jevbutton-left span,
.jevbutton-right span {
	display: block;
	height: 22px;
	float: left;
	line-height: 22px;
	font-size: 11px;
	color: #333;
	cursor: pointer;
}

.jevbutton-left span,
.jevbutton-right span { cursor: default; color: #999; }

.jevbutton-left .page a,
.jevbutton-right .page a,
.jevbutton-left .page span,
.jevbutton-right .page span,

.jevbutton-left a:hover,
.jevbutton-right a:hover { text-decoration: none; color: #0B55C4; }

.jevbutton-left a,
.jevbutton-left span { padding: 0 24px 0 6px; }

.jevbutton-right a,
.jevbutton-right span { padding: 0 6px 0 24px; }

.jevbutton-left { background: url(../images/jevbutton_left.png) no-repeat; float: left; margin-left: 5px; }

.jevbutton-right { background: url(../images/jevbutton_right.png) 100% 0 no-repeat; float: left; margin-left: 5px; }

.jevbutton-right .prev { background: url(../images/jevbutton_prev.png) no-repeat; }

.jevbutton-right.off .prev { background: url(../images/jevbutton_prev_off.png) no-repeat; }

.jevbutton-right .start { background: url(../images/jevbutton_first.png) no-repeat; }

.jevbutton-right.off .start { background: url(../images/jevbutton_first_off.png) no-repeat; }

.jevbutton-left .page { background: url(../images/jevbutton_right_cap.png) 100% 0 no-repeat; }

.jevbutton-left .next { background: url(../images/jevbutton_next.png) 100% 0 no-repeat; }

.jevbutton-left.off .next { background: url(../images/jevbutton_next_off.png) 100% 0 no-repeat; }

.jevbutton-left .end { background: url(../images/jevbutton_last.png) 100% 0 no-repeat; }

.jevbutton-left.off .end { background: url(../images/jevbutton_last_off.png) 100% 0 no-repeat; }

