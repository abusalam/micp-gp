<?php
	return [
		'register'       => [
			'title' => 'Login or Register yourself',
			'desc'  => 'To access assignments and get them evaluated.',
		],
		'home'           => [
			'welcome'    => 'Welcome to Gate Pass Issuing System',
			'disclaimer' => 'Contents owned, maintained and updated by - ',
		],
		'menu'           => [
			'menuTitle'         => 'Menu',
			'userManagement'    => 'User Management',
			'createTitle'       => 'Create New Account',
			'updateTitle'       => 'Update Account(Pending)',
			'updateProfile'     => 'My Profile',
			'contact' 					=> 'Contact Us',
			'booking'           => 'Gate Pass',
			'search'            => 'Check Gate Pass',
			'login'             => 'Login',
			'logout'            => 'Logout',
		],
		'menuAssignment' => [
			'groupTitle'    => 'Assignments',
			'myAssignments' => 'My Assignments',
			'newAssignment' => 'Create Assignment',
			'NewTopic'      => 'Create Topic',
		],
		'menuEvaluation' => [
			'groupTitle' => 'Evaluation',
			'myScores'   => 'My Report Card(Pending)',
			'myAnswers'  => 'My Answers',
		],
		'account'        => [
			'usersTitle'       => 'User Accounts',
			'createTitle'      => 'Create New Account',
			'createSuccess'    => 'User account created successfully.',
			'saveSuccess'      => 'User updated successfully!',
			'profileTitle'     => 'My Profile',
			'roleTitle'        => 'User Role',
			'roleOptTitle'     => 'Role',
			'schoolTitle'      => 'School',
			'classTitle'       => 'Class',
			'updateTitle'      => 'Update User Account',
			'weNeverShare'     => 'We\'ll never share mobile number or email id with anyone else.',
			'classNotRequired' => '<b>NB:</b> Class ↑ is only required for students.',
			'logout'           => 'Successfully Logged out!',
			'groupError'       => 'Invalid User Role.',
			'resendActivation' => 'Resend Activation E-Mail',
		],
		'profile'        => [
			'formTitle'    => 'My Profile',
			'fullName'     => 'Full Name',
			'profileTitle' => 'Update Profile',
			'updateTitle'  => 'Update Profile',
			'saveSuccess'  => 'Profile updated successfully!',
			'hasNoProfile' => 'Please update your profile!',
			'hasNoClass'   => 'Please update your Class!',
			'aboutMe'      => 'About Me',
			'descHelp'     => 'Tell us something about yourself.',
			'notRequired'  => 'Your profile is already updated. Profile update is allowed only once if required.',
		],
		'topic'          => [
			'createTitle'   => 'Add New Topic',
			'topicHelp'     => 'Take your time to plan and describe the topic in detail.',
			'newTitle'      => 'Assignment Title',
			'viewTitle'     => 'My Topics',
			'classTitle'    => 'Class',
			'subjectTitle'  => 'Subject',
			'topicTitle'    => 'Topic Title',
			'topicNew'      => 'New',
			'topicSave'     => 'Save Topic',
			'topicUpdate'   => 'Update Topic',
			'detailTitle'   => 'Topic Details',
			'detailHelp'    => 'Please provide details about the topic.',
			'createSuccess' => 'Added New Topic {id} <b>{topic}</b>',
		],
		'school'         => [
			'createTitle'   => 'Add School',
			'diseTitle'     => 'U-DISE',
			'listTitle'     => 'School Management',
			'schoolTitle'   => 'School',
			'updateTitle'   => 'Update School',
			'schoolSave'    => 'Save School',
			'showSchool'    => 'Inactive Schools(Pending)',
			'createSuccess' => 'Added New School {id} <b>{school}</b>',
			'updateSuccess' => 'Updated School {id} <b>{school}</b>',
			'unAuthorized'  => 'You are not authorized to view this school',
			'notFound'      => 'School not Found!',
		],
		'file'           => [
			'createTitle'      => 'Add File',
			'createHelp'       => 'File (Portrait A4 Page in {width}px × {height}px)',
			'listTitle'        => 'My Files',
			'btnFileTitle'     => 'Add New Assignment',
			'menuTitle'        => 'Manage Files',
			'menuShow'         => 'Show Files',
			'assignmentTitle'  => 'Assignment Title',
			'btnUpload'        => 'Upload',
			'btnDelete'        => 'Delete',
			'lblUpload'        => 'Select Image(.jpg)',
			'btnCreateTitle'   => 'Add File to Assignment',
			'fileInvalidError' => 'Invalid File Uploaded!',
			'createSuccess'    => 'Uploaded <b>{size}</b> file to <b>{file}</b>',
			'delSuccess'       => 'File deleted!',
			'rotSuccess'       => 'File rotated!',
			'unAuthorized'     => 'You are not authorized to view this file',
			'notFound'         => 'File not Found!',
		],
		'assignment'     => [
			'createTitle'     => 'Create New Assignment',
			'listTitle'       => 'My Assignments',
			'createHelp'      => 'As you work with your student, they will not be able to complete this entire list of assignments, so plan to spend more or less time on narrative to help build a student\'s confidence or to work on detail and focus. Similarly, spend more or less time on summary for those students who need more practice with critical reading and writing. Keep in mind that we would like to evaluate the student\'s readiness for the next courses.',
			'btnFileTitle'    => 'Upload Assignment Files',
			'menuTitle'       => 'Manage Assignments',
			'manageTitle'     => 'Manage Assignment',
			'menuTopics'      => 'Create Topic',
			'menuDraft'       => 'Update Topics',
			'menuComplete'    => 'Completed Assignments',
			'menuAnswer'      => 'Upload Assignment Answer',
			'newTitle'        => 'Assignment Title',
			'notFound'        => 'Assignment Not Found.',
			'unAuthorized'    => 'You are not authorized to view this assignment',
			'viewTitle'       => 'My Assignments',
			'marksTitle'      => 'Total Marks',
			'questions'       => 'Total Questions',
			'btnCreateTitle'  => 'Save Assignment',
			'btnLockTitle'    => 'Send to Students',
			'msgLocked'       => 'Assignment is already sent to students',
			'fileUploadError' => 'Error Uploading File!',
			'createSuccess'   => 'Added New Assignment {id} <b>{title}</b>',
			'lockSuccess'     => 'Assignment saved and sent to all students to submit answers.',
		],
		'answer'         => [
			'createTitle'      => 'Upload Solved Assignment',
			'listTitle'        => 'My Answers',
			'createHelp'       => 'As you work with your student, they will not be able to complete this entire list of assignments, so plan to spend more or less time on narrative to help build a student\'s confidence or to work on detail and focus. Similarly, spend more or less time on summary for those students who need more practice with critical reading and writing. Keep in mind that we would like to evaluate the student\'s readiness for the next courses.',
			'btnFileTitle'     => 'Submit Solved Assignment',
			'menuTitle'        => 'Manage Answers',
			'menuUpdate'       => 'Update Answers',
			'menuClose'        => 'Delete Answers',
			'newTitle'         => 'Assignment Title',
			'notFound'         => 'Answer Not Found.',
			'unAuthorized'     => 'You are not authorized to view this answer',
			'viewTitle'        => 'My Answers',
			'marksTitle'       => 'Marks',
			'btnCreateTitle'   => 'Send for Evaluation',
			'msgLocked'        => 'Answer is already sent for evaluation',
			'fileUploadError'  => 'Error Uploading File!',
			'createSuccess'    => 'Added New Assignment {id} <b>{title}</b>',
			'checkAnswers'     => 'Check All Submitted Answers',
			'checkAnswer'      => 'Check Answer',
			'noCanvasSupport'  => 'Canvas is not supported in your browser please update to latest browser',
			'checkSave'        => 'Save Checked Answer',
			'checkSaveDone'    => 'Checked answer saved successfully!',
			'solvedAnswers'    => 'Answers',
			'myMarks'          => 'Marks',
			'lockSuccess'      => 'Answer saved and sent to teacher for evaluation.',
			'question'         => 'Questions:',
			'markSheet'        => 'Marksheet',
			'marksObtained'    => 'Total:',
			'saveMarks'        => 'Save',
			'marksSaveSuccess' => 'Marksheet Saved Successfully!',
			'invalidQuestions' => 'Questions not found!',
			'tooManyQuestions' => 'Number of questions not matching with assignment!',
			'tooMuchMarks'     => 'Invalid total marks given.',
		],
		'eval'           => [
			'listTitle' => 'Answers for Assignment',
		],
		'booking'     => [
			'createTitle'     => 'Gate Pass',
			'createHelp'      => 'Pass validity is 30days from date of issue.',
			'vehicleNo'       => 'Vehicle No',
			'driverTitle'     => 'Driver Details',
			'crewTitle'       => 'Crew Details',
			'driverName'      => 'Name',
			'driverAddress'   => 'Address',
			'driverMobile'    => 'Mobile',
			'driverLicense'   => 'Driving License No',
			'crewName'        => 'Name',
			'crewAddress'     => 'Address',
			'crewMobile'      => 'Mobile',
			'crewIdCardType'  => 'ID Card Type',
			'crewIdCardNo'    => 'ID Card No',
			'btnCreateTitle'  => 'Print Gate Pass',
			'btnCheckTitle'   => 'Check Availability',
			'btnPayTitle'     => 'Pay ₹{amount}',
			'btnSearchTitle'  => 'Search Ticket',
			'date'            => 'Date (dd/mm/yyyy)',
			'startTime'       => 'Start Time',
			'hours'           => 'Duration',
			'firstSlot'       => 'Morning (08:00AM - 12:00PM)',
			'nextSlot'        => 'Evening (04:00PM - 08:00PM)',
			'purpose'         => 'Purpose',
			'notFound'        => 'Booking Not Found.',
			'searchTitle'     => 'Search Ticket',
			'searchHelp'      => 'Ticket No. is available on the booking receipt.',
			'ticket'          => 'Ticket No.',
		],
	];