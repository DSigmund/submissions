# TODO

## Files

- Correct Field Names from Database

- All Excel-Files

- wordpress htaccess -> ignore folder submissions

## Info

- Get Form IDs: `SELECT * FROM Td6PNmU6_posts WHERE post_type="wpcf7_contact_form"`
- Get Form Title: `SELECT post_title FROM Td6PNmU6_posts WHERE post_type="wpcf7_contact_form" AND id=".$id`
- Get all Values for all Submission: `SELECT data_id, name, value FROM Td6PNmU6_cf7_vdata_entry WHERE cf7_id=".$id." AND name NOT LIKE "\_%" ORDER BY data_id ASC, name ASC`
- Get Values for one Submission: `SELECT name, value FROM Td6PNmU6_cf7_vdata_entry WHERE data_id=".$id." AND name NOT LIKE "\_%" ORDER BY name ASC`
- Get HeaderInfo for Submissions in Form: `SELECT v.id, v.created, e.value AS Hint FROM Td6PNmU6_cf7_vdata v, Td6PNmU6_cf7_vdata_entry e WHERE e.cf7_id=".$id." AND e.data_id = v.id AND (e.name = 'TitleInEnglish' OR e.name = 'EntryTitle')"`

