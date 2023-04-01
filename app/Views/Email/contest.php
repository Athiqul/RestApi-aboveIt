<!DOCTYPE html>

<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
      rel="icon"
      href="/image/logo-mini.svg"
      type="image/x-icon"
    />

    <title>Above IT | Email Confirmation for Contest</title>

    <link href="/css/css2.css" rel="stylesheet" />

    <style type="text/css">
      body {
        /* text-align: center; */
        margin: 0 auto;
        width: 650px;
        font-family: "Rubik", sans-serif;
        background-color: #e2e2e2;
        display: block;
      }

      ul {
        margin: 0;
        padding: 0;
      }

      li {
        display: inline-block;
        text-decoration: unset;
      }

      a {
        text-decoration: none;
      }

      h5 {
        margin: 10px;
        color: #777;
      }

      .text-center {
        text-align: center;
      }

      .main-bg-light {
        background-color: #fafafa;
      }

      h4.title {
        color: #fff;
        font-weight: bold;
        padding-bottom: 0;
        text-transform: capitalize;
        display: inline-block;
        letter-spacing: 1.5px;
        position: relative;
        padding-bottom: 5px;
        border-bottom: 2px solid #fff;
      }

      .header .header-logo a {
        display: inline-block;
        /* background-color: #212529; */
        margin: 0 auto;
        padding: 40px 30px;
      }

      .header .header-contain h5 {
        margin: 0;
        font-size: 20px;
        color: #212529;
        letter-spacing: 4px;
        font-weight: 800;
        text-transform: uppercase;
      }

      .header .header-contain h2 {
        margin: 12px 0 0;
        font-size: 30px;
        color: #212529;
        letter-spacing: 7px;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1;
      }

      .title-2 h2 {
        margin: 0;
        font-size: 26px;
        color: #212529;
        letter-spacing: 1px;
        font-weight: 800;
        text-transform: uppercase;
        line-height: 1;
      }

      .title-2 p {
        font-size: 14px;
        width: 80%;
        letter-spacing: 1px;
        margin: 20px auto 0;
        line-height: 1.4;
        color: #7e7e7e;
      }

      .title-2 button {
        color: #fff;
        letter-spacing: 2px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 16px;
        border-radius: 50px;
        padding: 15px 35px;
        border: 1px solid #212529;
        background-color: #212529;
        font-size: 10px;
      }

      .header .header-contain button {
        color: #212529;
        letter-spacing: 2px;
        font-weight: 600;
        text-transform: uppercase;
        margin: 16px 0;
        border-radius: 50px;
        padding: 15px 35px;
        background-color: #fff;
        border: 1px solid #212529;
      }

      .contact-table {
        width: 100%;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .contact-table td {
        margin-left: 17px;
        position: relative;
        font-size: 13px;
        text-transform: uppercase;
        color: #ddd;
        letter-spacing: 1.1px;
      }

      .contact-table td:after {
        content: "";
        position: absolute;
        top: 50%;
        left: -10px;
        border-radius: 50%;
        background-color: #fff;
        width: 3px;
        height: 3px;
        transform: translateY(-50%);
      }

      .contact-table td:first-child:after {
        content: unset;
      }

      .footer-social-icon tr td {
        width: 30px;
        height: 30px;
        background-color: transparent;
        border-radius: 50%;
        color: #ddd;
        margin-right: 15px;
      }

      .footer-social-icon tr td i {
        width: 50%;
        margin: 0;
      }
    </style>
  </head>

  <body style="margin: 20px auto">
    <table
      align="center"
      border="0"
      cellpadding="0"
      cellspacing="0"
      style="
        background-color: #eff2f7;
        box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);
        -webkit-box-shadow: 0px 0px 14px -4px rgba(0, 0, 0, 0.2705882353);
      "
    >
      <tbody>
        <tr>
          <td>
            <table
              align="center"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
            >
              <tbody>
                <tr class="header">
                  <td
                    align="left"
                    class="header-logo"
                    style="
                      text-align: center;
                      display: block;
                      margin-bottom: 20px;
                    "
                    valign="top"
                  >
                    <a href="abovebd.com">
                      <img
                        src="./aboveit_mail_template/logo-white.png"
                        class="main-logo"
                        alt=""
                      />
                    </a>
                  </td>
                  <td class="header-contain" style="display: block">
                    <ul>
                      <li style="display: block; text-decoration: unset">
                        <h5 style="text-align: center">Contest</h5>
                      </li>

                      <li style="display: block; text-decoration: unset">
                        <h2 style="text-align: center">UI/UX Design</h2>
                      </li>
                    </ul>
                  </td>
                </tr>
              </tbody>
            </table>
            <table
              class="main-bg-light"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              style="margin-top: 40px"
            ></table>
            <div class="title title-2">
              <h2 style="text-align: center">this is just the beginning.</h2>
              <p
                style="
                  padding-left: 20px;
                  float: left;
                  font-size: 15px;
                  width: 90%;
                  letter-spacing: 1px;
                  margin: 40px auto 40px;
                  line-height: 1.4;
                  color: #7e7e7e;
                "
              >
                Dear <?=$candidate->participant_name?><br />
                Thank you for your interest in participating in our  <?=($candidate->contest_type=="0")?"UI UX Design":"Frontend Developer"?> Contest. We are pleased to inform
                you that your application has been received and accepted.
                <br /><br />
                As a reminder, the contest will challenge you to showcase your
                skills in UI/UX Design. We are eager to see your unique and
                creative solutions to real-world problems.
                <br /><br />
                Please be on the lookout for another email from us in the next
                few days. This email will contain the task and submission link,
                as well as the deadline for your submission.
              </p>
            </div>
            <table
              align="center"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
            >
              <tbody>
                <tr>
                  <td>
                    <img
                      src="./aboveit_mail_template/offer.jpg"
                      alt=""
                      style="width: 100%; height: 100%"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="title title-2">
              <p
                style="
                  padding-left: 20px;
                  float: left;
                  font-size: 15px;
                  width: 90%;
                  letter-spacing: 1px;
                  margin: 40px auto 0;
                  line-height: 1.4;
                  color: #7e7e7e;
                "
              >
                We are excited to see your participation in this contest and
                look forward to your submission. If you have any questions or
                concerns, please do not hesitate to reach out to us at
                <a style="color: #ee1c4a" href="mailto:info@abovebd.com"
                  >info@abovebd.com</a
                >
              </p>
            </div>
            <table
              class="main-bg-light"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              style="margin-top: 40px"
            ></table>

            <table
              class="text-center"
              align="center"
              border="0"
              cellpadding="0"
              cellspacing="0"
              width="100%"
              style="
                margin-top: 40px;
                background-color: #212529;
                color: #fff;
                padding: 40px 0;
              "
            >
              <tbody>
                <tr>
                  <td>
                    <div>
                      <h4 class="title" style="margin: 0; text-align: center">
                        Follow us
                      </h4>
                    </div>
                    <table
                      border="0"
                      cellpadding="0"
                      cellspacing="0"
                      class="footer-social-icon"
                      align="center"
                      style="margin: 20px auto"
                    >
                      <tbody>
                        <tr>
                          <td>
                            <img
                              src="/images/fb.png"
                              alt=""
                              style="
                                font-size: 25px;
                                margin: 0 10px 0 10px;
                                width: 22px;
                                filter: invert(1);
                              "
                            />
                          </td>
                          <td>
                            <img
                              src="/images/linkedin.png"
                              alt=""
                              style="
                                font-size: 25px;
                                margin: 0 10px 0 10px;
                                width: 22px;
                                filter: invert(1);
                              "
                            />
                          </td>
                          <td>
                            <img
                              src="/imagestwitter.png"
                              alt=""
                              style="
                                font-size: 25px;
                                margin: 0 10px 0 10px;
                                width: 22px;
                                filter: invert(1);
                              "
                            />
                          </td>
                          <td>
                            <a
                              href="http://"
                              target="_blank"
                              rel="noopener noreferrer"
                            >
                              <img
                                src="/images/insta.png"
                                alt=""
                                style="
                                  font-size: 25px;
                                  margin: 0 10px 0 10px;
                                  width: 22px;
                                  filter: invert(1);
                                "
                              />
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <table
                      border="0"
                      cellpadding="0"
                      cellspacing="0"
                      width="100%"
                    >
                      <tbody>
                        <tr>
                          <td>
                            <h5
                              style="
                                font-size: 15px;
                                text-transform: uppercase;
                                margin: 0;
                                color: #ddd;
                                letter-spacing: 1.8px;
                              "
                            >
                              shop for
                              <span style="color: #e22454">Above IT</span>
                            </h5>
                          </td>
                        </tr>
                        <tr>
                          <td style="width: 100%">
                            <table class="contact-table">
                              <tbody style="display: block; width: 100%">
                                <tr
                                  style="
                                    display: block;
                                    width: 100%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                  "
                                >
                                  <td>Contact Us</td>
                                  <td>Privacy Policy</td>
                                  <td>Unsubscribe</td>
                                </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>
