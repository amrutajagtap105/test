<?php

function dcount($link, $userid) {

    $q3 = "SELECT 
            COUNT(CASE WHEN document.`status` = 'Unlocked' THEN 1 ELSE NULL END) AS Unlocked,
            COUNT(CASE WHEN document.`status` = 'Locked' THEN 1 ELSE NULL END) AS Locked,
            COUNT(CASE WHEN document.`status` = 'Processed' THEN 1 ELSE NULL  END) AS Processed,
            COUNT(CASE WHEN document.`status` = 'Rejected' THEN 1 ELSE NULL END) AS Rejected,
            COUNT(CASE WHEN document.`status` = 'Checked' THEN 1 ELSE NULL END) AS Verified
            FROM document 
            left join user on user.id =document.lockedby 
            left join client on client.id = document.clientid
            where 
                client.providerid=(select providerid from user where id=$userid)
                    and
            (
                lockedby is null 
                    or
                lockedby=$userid
                    or
                $userid in (select id from user where admin=1)
            )
            ";
    if (!$req3 = mysqli_query($link, $q3)) {
        recorddie(__LINE__, __FILE__, $q3);
        errorMessage('SYSTEM ERROR. Could not get document details');
    }
    if (!$row3 = mysqli_fetch_assoc($req3)) {
        errorMessage('Invalid. Document details not found');
    }
    $object = new stdClass();
    $object->Unlocked = $row3['Unlocked'];
    $object->Locked = $row3['Locked'];
    $object->Processed = $row3['Processed'];
    $object->Rejected = $row3['Rejected'];
    $object->Verified = $row3['Verified'];
    return $object;
}
