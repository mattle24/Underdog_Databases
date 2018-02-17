<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Terms</title>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php
	if (!isset($_SESSION['logged_user'])){
		include 'includes/navbar.php';
	}
	else {include 'includes/navbar_loggedin.php';}
	?>
    <div id = 'page-header0'>
        <div class = 'spacer'> </div>

        <div id = 'white-container-medium'>
            <div>
                <h4>1. Terms</h4>
                <p>By accessing the Underdog Databases website and/or services, you are agreeing to be bound by these terms of service and all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this website are protected by applicable copyright and trademark law.  You may not use the website or services if you are under the age of 13.  If you are under the age of 18, you represent that a parent or legal guardian also agrees to these terms on your behalf.</p>
            </div>

            <div>
                <h4>2. Use License</h4>
                <p>Permission is granted to temporarily download one copy of the materials (information or software) on Underdog Databases' website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
                    <ul>
                        <li>modify or copy the materials</li>
                        <li>use the materials or the services for any commercial (profit-generating) purpose, or for any public display (commercial or non-commercial); </li>
                        <li>resell the services or any service based on the services;</li>
                        <li>attempt to decompile or reverse engineer any software contained on or accessible through the Underdog Databases website or services;</li>
                        <li>remove any copyright or other proprietary notations from the materials; or</li>
                        <li>transfer the materials to another person or "mirror" the materials or software on any other server.</li>
                    </ul>
                This license shall automatically terminate if you violate any of these restrictions and may be terminated by us at any time. Upon any termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.</p>
            </div>

            <div>
                <h4>3. Disclaimer</h4>
                <p>The materials and services on Underdog Databases' website are provided on an 'as is' and ‘as available’ basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties including, without limitation, implied warranties or conditions of merchantability, suitability, quality, performance, service availability or reliability, security, timeliness, freedom from error, completeness, accuracy of results, fitness for a particular purpose, course of dealing, or non-infringement of intellectual property or other violation of rights.</p>
                <p>Further, we do not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the Underdog Databases materials or services or otherwise relating to such materials or services whether on this website or on any sites linked to this site.</p>
            </div>

            <div>
                <h4>4. Limitations</h4>
                <p> In no event shall we or any of our suppliers or affiliates be liable for any damages, whether direct, indirect, special, consequential, or otherwise (including, without limitation, damages for loss of use, data, revenue or profit, cost of replacement, or due to business interruption) arising out of the use or inability to use the materials or services on Underdog Databases' website, even if we or an authorized representative of ours has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.  In no event shall our liability for any use of our website or services exceed the fees and other amounts paid by you in respect thereof.</p>
            </div>

            <div>
                <h4>5. Accuracy of materials and services; Changes</h4>
                <p>The materials appearing on Underdog Databases website could include technical, typographical, or photographic errors, and the services provided could contain technical, logical, analytical or other errors that could cause failures, corruption or loss of data and/or information. We do not warrant that any of the materials on the website are accurate, complete or current, or that any of the services will perform without error or defect. We may make changes to the materials and services contained on the website at any time without notice, but do not make any commitment to update or correct the materials or services for any reason.  We also reserve the right to modify, suspend or stop providing the Underdog Databases’ website or services (or any part thereof), either temporarily or permanently, at any time or from time to time, with or without prior notice to you, and shall not be responsible for providing access to any content or information contained therein, including any such content or information provided by you or created through your use of the website or services.</p>
            </div>

            <div>
                <h4>6. Fees</h4>
                <p>We reserve the right to change any fees or applicable charges for future access to and use of the Underdog Databases website and services upon notification to you.</p>
            </div>

            <div>
                <h4>7. User Accounts</h4>
                <p>In order to use the Underdog Databases services, you will have to register and create a user account.  Users must ensure their contact information is accurate and updated at all times.  We reserve the right to refuse registration of, or cancel or require change of, any account names or passwords we deem inappropriate in our reasonable discretion.  You are responsible for all activities conducted through your account. You may not permit other individuals to use your account; if you do so, you will be responsible for all liabilities arising from such use including any failure by them to adhere to these terms of service. You agree to notify us immediately of any unauthorized access or use of your password or account, or of any other breach of security.  To the extent your account  is authorized to take actions which affect the inclusion or level of access of other user accounts, you are responsible for all such actions.</p>
            </div>

            <div>
                <h4>8. User-Provided Content</h4>
                <p>You are solely responsible for all information and data (collectively, “Content”) provided, created or used in connection with the use of the Underdog Databases services by you or on your behalf. You grant us a fully paid up, transferable, sublicensable, right and license to store, modify, reformulate and otherwise use all such Content for any lawful purpose related to the provision, operation, or maintenance of the services. You acknowledge and agree that we may in our sole discretion disclose any such Content if in our good faith belief such preservation or disclosure is reasonably necessary to: (a) comply with legal process, applicable laws or government requests; or (b) respond to claims that any Content violates the rights of third parties.   You acknowledge that we does not pre-screen Content, but that we and our designees will have the right (but not the obligation) in their sole discretion to refuse, block or remove any Content, including any Content that violates these terms of service or is deemed by us or our designees, in their sole discretion, to be objectionable.</p>
            </div>

            <div>
                <h4>9. Intellectual Property</h4>
                <p>The technology and software underlying the Underdog Databases’ website and services is the property of us or our affiliates, and our licensors and suppliers and is or may be protected by copyright, patent, trademark, trade secret or other intellectual proprietary rights and laws.  Any improvements to the software or the services including but not limited to, modifications, upgrades, fixes, derivative works and additions thereto, and any new know-how and new technical information developed or discovered by us in the course of the provision of the services, including those based in whole or in part on any feedback, suggestions, recommendations or improvements that you may provide us, are and will be our sole and exclusive property (and to the extent required in connection with the foregoing, upon any delivery thereof to us you are hereby deemed to grant to us an irrevocable, perpetual, royalty-free right and license to use all such feedback, suggestions, recommendations or improvements for any purpose whatsoever).</p>
            </div>

            <div>
                <h4>10. Links</h4>
                <p>Links to other websites: We have not reviewed all of the sites linked to this website and are not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by us of the site. Use of any such linked website is at the user's own risk.</p>
                <p>Links to Underdog Databases website:  We permit anyone to link to the Underdog Databases website subject to the linker´s compliance with the following terms and conditions:
                    <ul>
                        <li>May link to, but not replicate, content contained in Underdog Databases website</li>
                        <li>Must not create a border environment or browser around content contained in or services provided through Underdog Databases site</li>
                        <li>Must not present misleading or false information about Underdog Databases</li>
                        <li>Must not misrepresent linker’s relationship with Underdog Databases</li>
                        <li>Must not imply that Underdog Databases is endorsing or sponsoring the linker or the linker´s services or products</li>
                        <li>Must not use Underdog Databases logos or trademarks without prior written permission</li>
                        <li>Must not contain content that could be construed as obscene, libelous, defamatory, pornographic, or inappropriate for all ages</li>
                        <li>Must not contain materials or services that would violate any laws</li>
                        <li>Must remove link at any time upon our request</li>
                    </ul>
                </p>
            </div>

            <div>
                <h4>11. Modifications; Survival; Entire Agreement</h4>
                <p>We may revise these terms of service at any time without notice. By using this website you are agreeing to be bound by the then-current version of these terms of service.  We shall not be deemed to have waived any right hereunder unless we do so in writing. The waiver by us of a breach or a default of any provision shall not be construed as a waiver of any succeeding breach of the same or any other provision. Any provisions of these terms of service which by their nature should survive the expiration or termination of these terms of service and the use of the website or services shall survive such expiration or termination.  These terms of service constitute the entire understanding with respect to the matters contemplated hereby, supersedes all previous understandings and agreements between you and us concerning the subject matter hereof and cannot be amended except in writing.</p>
            </div>

            <div>
                <h4>12. Governing Law</h4>
                <p>These terms and conditions are governed by and construed in accordance with the laws of New York and you irrevocably submit to the exclusive jurisdiction of the federal and state courts in that State.</p>
            </div>

        </div>
        <div class = 'spacer'> </div>
    </div>
</body>

</html>
