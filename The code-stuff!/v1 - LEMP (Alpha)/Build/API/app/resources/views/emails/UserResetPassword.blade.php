

{{--


	THIS IS VERY MUCH JUST FOUNDATION TEMPLATE - ADD A FEW AREAS WHERE THE STYLES CAN BE DROPPED-IN RATHER THAN THE EMAIL BLADE FILE INCLUDING THE SAME STUFF ALL THE TIME

	MAKE SURE THAT EMAIL WAS SENT IN QUEUE (DOESN'T SLOW THE SERVER)

	CREATE THE PASSWORD RESET EMAIL TOO!!!!


--}}



<!DOCTYPE html>
<html>
  <head>
    <meta name='viewport' content='width=device-width' />
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>{{ $emailData['subject'] }}</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */

      /*All the styling goes here*/

      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%;
      }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%;
	}
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top;
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #ffffff;
        width: 100%;
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px;
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 580px;
        padding: 10px;
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%;
      }

      .wrapper {
        box-sizing: border-box;
        padding: 0px 0px 0px 0px;
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

	  .zero-pb {
		  padding-bottom: 0px;
	  }

	  .zero-pt {
		  padding-top: 0px;
	  }

	  .zero-ps {
		  padding-top: 4px;
	  }

      .footer {
        clear: both;
		margin: 20px 0px 20px 0px;
		padding: 20px 0px 20px 0px;
        text-align: left;
        width: 100%;
		border-top: 1px solid #cfcfcf;
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: left;
      }

	  .add-mb {
		  margin-bottom: 26px;
	  }

	  .add-mt {
		  margin-top: 36px;
	  }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px;
      }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: left;
        text-transform: capitalize;
      }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px;
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px;
      }

      a {
        color: #735fd7;
        text-decoration: underline;
      }

	  p.bold {
		  font-weight: 800;
		  font-size: 16px;
	  }

	  p.team {
		 margin-top: -10px;
		 font-weight: 800;
		 font-size: 15px;
	 }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto;
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 4px;
          text-align: left;
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #735fd7;
          border-radius: 4px;
          box-sizing: border-box;
          color: #735fd7;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 18px;
          text-decoration: none;
          text-transform: capitalize;
      }

      .btn-primary table td {
        background-color: #735fd7;
      }

      .btn-primary a {
        background-color: #735fd7;
        border-color: #735fd7;
        color: #ffffff;
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0;
      }

      .first {
        margin-top: 0;
      }

      .align-center {
        text-align: center;
      }

      .align-right {
        text-align: right;
      }

      .align-left {
        text-align: left;
      }

      .clear {
        clear: both;
      }

      .mt0 {
        margin-top: 0;
      }

      .mb0 {
        margin-bottom: 0;
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0;
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        margin: 20px 0;
      }

	  .footer-logo {
		  width: 200px;
		  height: auto;
	  }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important;
        }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important;
        }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important;
        }
        table[class=body] .content {
          padding: 0 !important;
        }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important;
        }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important;
        }
        table[class=body] .btn table {
          width: 100% !important;
        }
        table[class=body] .btn a {
          width: 100% !important;
        }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important;
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%;
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%;
        }
        #MessageViewBody a {
          color: inherit;
          text-decoration: none;
          font-size: inherit;
          font-family: inherit;
          font-weight: inherit;
          line-height: inherit;
        }
        .btn-primary table td:hover {
          background-color: #735fd7 !important;
        }
        .btn-primary a:hover {
          background-color: #735fd7 !important;
          border-color: #735fd7 !important;
        }
      }

    </style>
  </head>
  <body class=''>
    <span class='preheader'>{{ $emailData['message']['previewMessage'] }}</span>
    <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='body'>
      <tr>
        <td>&nbsp;</td>
        <td class='container'>
          <div class='content'>

            <!-- START CENTERED WHITE CONTAINER -->
            <table role='presentation' class='main'>

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class='wrapper'>
                  <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                    <tr>
                      <td>
                        <p></p>
						<p class='bold'>Hello {{ $emailData['message']['name'] }}!</p>

                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

                        <table role='presentation' border='0' cellpadding='0' cellspacing='0' class='btn btn-primary add-mt add-mb'>
                          <tbody>
                            <tr>
                              <td align='left'>
                                <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
                                  <tbody>
                                    <tr>
                                      <td> <a href='{{ env('WWW_URL') }}' target='_blank'>Call To Action</a> </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
						<p>Catch you later!</p>
                        <p class='team'>Team AtomizeCRM</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->

            <!-- START FOOTER -->
            <div class='footer'>
              <table role='presentation' border='0' cellpadding='0' cellspacing='0'>
				<tr>
                   <td class='content-block zero-pb'>
                      <img class='footer-logo' src='{{ env('WWW_URL') }}/img/email-logo.png'>
  				  </td>
			  	</tr>
			  	<tr>
				  <td class='content-block zero-ps'>
                    <p style='color:#000000;max-width:200px;margin-top:2px;'>{{ env('MARKETING_MESSAGE') }}</p>
				 </td>
  				</tr>
				<tr>
                   <td class='content-block zero-pb'>
                      <span>Copyright &copy; {{ date('Y', time()) }}, All rights reserved.</span>
  				  </td>
  				</tr>
				<tr>
                  <td class='content-block zero-ps'>
                    <span>Sent by <a href='{{ env('WWW_URL') }}'>AtomizeCRM</a></span>
				 </td>
				</tr>
                  <td class='content-block'>
                    <a href='{{ env('WWW_URL') }}'>Unsubscribe</a>.
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->

          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
