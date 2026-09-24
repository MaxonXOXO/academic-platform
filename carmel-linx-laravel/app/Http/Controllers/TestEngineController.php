<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\PushNotificationService;
use Carbon\Carbon;

class TestEngineController extends Controller
{
    // Embedded Systems MCQ Pool (isolated strictly to Embedded Systems subjects)
    private $embeddedSystemsMCQs = [
        'CO1' => [
            ['q' => 'Which of the following is a primary feature of an embedded system?', 'options' => ['High power consumption', 'General purpose computing', 'Real-time performance constraints', 'Requires a monitor'], 'ans' => 'Real-time performance constraints'],
            ['q' => 'What is the function of a watchdog timer?', 'options' => ['Keep real time', 'Reset the system on software hang', 'Manage battery life', 'Increase CPU speed'], 'ans' => 'Reset the system on software hang'],
            ['q' => 'Which memory is typically used to store the application firmware?', 'options' => ['SRAM', 'EEPROM', 'Flash', 'DRAM'], 'ans' => 'Flash'],
            ['q' => 'An embedded system must be...', 'options' => ['Application specific', 'Tightly constrained', 'Reactive to environment', 'All of the above'], 'ans' => 'All of the above'],
            ['q' => 'Which bus architecture uses separate paths for data and instructions?', 'options' => ['Von Neumann', 'Harvard', 'PCI', 'USB'], 'ans' => 'Harvard'],
            ['q' => 'What is the most important characteristic of a hard real-time system?', 'options' => ['High throughput', 'Low cost', 'Strict timing deadlines', 'Large memory'], 'ans' => 'Strict timing deadlines'],
            ['q' => 'Which processor architecture is most commonly used in mobile embedded systems?', 'options' => ['x86', 'ARM', 'MIPS', 'PowerPC'], 'ans' => 'ARM'],
            ['q' => 'What type of memory is volatile?', 'options' => ['SRAM', 'EEPROM', 'Flash', 'ROM'], 'ans' => 'SRAM'],
            ['q' => 'An RTOS is required when...', 'options' => ['System needs a GUI', 'System has strict timing constraints', 'System uses a lot of memory', 'System is connected to the internet'], 'ans' => 'System has strict timing constraints'],
            ['q' => 'Which interface is typically used for debugging embedded systems?', 'options' => ['HDMI', 'JTAG', 'PCIe', 'SATA'], 'ans' => 'JTAG']
        ],
        'CO2' => [
            ['q' => 'What is the width of an AVR general purpose register?', 'options' => ['8-bit', '16-bit', '32-bit', '64-bit'], 'ans' => '8-bit'],
            ['q' => 'Which register holds the status flags in AVR?', 'options' => ['PC', 'SP', 'SREG', 'TCNT'], 'ans' => 'SREG'],
            ['q' => 'What is the size of Flash memory in Atmega32?', 'options' => ['8 KB', '16 KB', '32 KB', '64 KB'], 'ans' => '32 KB'],
            ['q' => 'How many I/O pins are available in Atmega32?', 'options' => ['16', '32', '40', '64'], 'ans' => '32'],
            ['q' => 'Which flag is set when an arithmetic operation results in a zero?', 'options' => ['Carry Flag', 'Zero Flag', 'Sign Flag', 'Overflow Flag'], 'ans' => 'Zero Flag'],
            ['q' => 'What is the function of the Program Counter (PC)?', 'options' => ['Store data', 'Point to the next instruction', 'Store status flags', 'Manage stack'], 'ans' => 'Point to the next instruction'],
            ['q' => 'Which register is used to configure a pin as input or output in AVR?', 'options' => ['PORT', 'PIN', 'DDR', 'SREG'], 'ans' => 'DDR'],
            ['q' => 'What does the VCC pin do?', 'options' => ['Ground', 'Power supply', 'Clock input', 'Reset'], 'ans' => 'Power supply'],
            ['q' => 'Which feature allows the microcontroller to save power?', 'options' => ['Sleep modes', 'High clock speed', 'More RAM', 'External interrupts'], 'ans' => 'Sleep modes'],
            ['q' => 'What is the maximum operating frequency of Atmega32?', 'options' => ['1 MHz', '8 MHz', '16 MHz', '32 MHz'], 'ans' => '16 MHz']
        ],
        'CO3' => [
            ['q' => 'What does PWM stand for?', 'options' => ['Power Width Measurement', 'Pulse Width Modulation', 'Phase Wave Modulation', 'Periodic Width Modulation'], 'ans' => 'Pulse Width Modulation'],
            ['q' => 'Which component is used to isolate high voltage circuits from microcontrollers?', 'options' => ['Capacitor', 'Inductor', 'Optocoupler', 'Resistor'], 'ans' => 'Optocoupler'],
            ['q' => 'A stepper motor is preferred for...', 'options' => ['High speed rotation', 'Precise angular positioning', 'High torque at high speeds', 'Continuous unmonitored rotation'], 'ans' => 'Precise angular positioning'],
            ['q' => 'Which pins are used for I2C communication?', 'options' => ['TX, RX', 'MOSI, MISO', 'SDA, SCL', 'PWM, ADC'], 'ans' => 'SDA, SCL'],
            ['q' => 'Debouncing is primarily required when interfacing...', 'options' => ['LEDs', 'Motors', 'Mechanical Switches', 'LCDs'], 'ans' => 'Mechanical Switches'],
            ['q' => 'What is the purpose of an ADC?', 'options' => ['Convert analog signals to digital', 'Convert digital signals to analog', 'Amplify signals', 'Filter noise'], 'ans' => 'Convert analog signals to digital'],
            ['q' => 'Which communication protocol is full-duplex?', 'options' => ['SPI', 'I2C', '1-Wire', 'CAN'], 'ans' => 'SPI'],
            ['q' => 'What does UART stand for?', 'options' => ['Universal Asynchronous Receiver/Transmitter', 'Uniform Analog Routing Technology', 'Universal Active Radio Transmission', 'None of the above'], 'ans' => 'Universal Asynchronous Receiver/Transmitter'],
            ['q' => 'A pull-up resistor is used to...', 'options' => ['Increase current', 'Define a default HIGH state', 'Filter high frequencies', 'Protect from overvoltage'], 'ans' => 'Define a default HIGH state'],
            ['q' => 'Which sensor is commonly used to measure temperature?', 'options' => ['LDR', 'LM35', 'Ultrasonic', 'PIR'], 'ans' => 'LM35']
        ],
        'CO4' => [
            ['q' => 'What is the core function of an RTOS?', 'options' => ['Providing a GUI', 'File management', 'Meeting real-time deadlines', 'Network routing'], 'ans' => 'Meeting real-time deadlines'],
            ['q' => 'What is a semaphore used for?', 'options' => ['Speeding up execution', 'Synchronizing tasks/protecting resources', 'Memory allocation', 'Storing task context'], 'ans' => 'Synchronizing tasks/protecting resources'],
            ['q' => 'Priority inversion is solved by...', 'options' => ['Priority inheritance', 'Round robin scheduling', 'Disabling interrupts', 'Increasing clock speed'], 'ans' => 'Priority inheritance'],
            ['q' => 'A task in an RTOS that is waiting for a timer to expire is in which state?', 'options' => ['Running', 'Ready', 'Blocked', 'Suspended'], 'ans' => 'Blocked'],
            ['q' => 'Which scheduling algorithm runs tasks for a fixed time slice?', 'options' => ['Rate Monotonic', 'Earliest Deadline First', 'Round Robin', 'First Come First Serve'], 'ans' => 'Round Robin'],
            ['q' => 'What is context switching?', 'options' => ['Changing power states', 'Saving current task state and loading another', 'Switching hardware ports', 'Updating firmware'], 'ans' => 'Saving current task state and loading another'],
            ['q' => 'Which of the following is a type of IPC?', 'options' => ['Message Queues', 'ADC', 'PWM', 'Watchdog'], 'ans' => 'Message Queues'],
            ['q' => 'A mutex is similar to a binary semaphore but includes...', 'options' => ['Priority inheritance', 'Multiple counts', 'Faster execution', 'Less memory usage'], 'ans' => 'Priority inheritance'],
            ['q' => 'What does preemptive scheduling mean?', 'options' => ['Tasks run until completion', 'Higher priority tasks can interrupt lower priority tasks', 'Tasks are scheduled randomly', 'Tasks share CPU equally'], 'ans' => 'Higher priority tasks can interrupt lower priority tasks'],
            ['q' => 'Which state is a task in when it is first created but not yet scheduled?', 'options' => ['Running', 'Ready', 'Blocked', 'Suspended'], 'ans' => 'Ready']
        ]
    ];

    // Dedicated C Programming MCQ Pool for Course 3045 (Fundamentals of C Programming)
    private $cProgrammingMCQs = [
        'CO1' => [
            ['q' => 'Which format specifier is used to print or scan an integer value in C?', 'options' => ['%d', '%f', '%c', '%s'], 'ans' => '%d'],
            ['q' => 'Which of the following is NOT a valid identifier or variable name in C?', 'options' => ['int', '_count', 'total_1', 'myVar'], 'ans' => 'int'],
            ['q' => 'What is the size of a float data type in standard 32-bit C architecture?', 'options' => ['4 bytes', '2 bytes', '8 bytes', '1 byte'], 'ans' => '4 bytes'],
            ['q' => 'Which operator is used in C to determine the size of a data type or variable in bytes?', 'options' => ['sizeof', 'len', 'size', 'lengthof'], 'ans' => 'sizeof'],
            ['q' => 'Which header file is required to use the standard I/O functions printf() and scanf()?', 'options' => ['<stdio.h>', '<conio.h>', '<stdlib.h>', '<string.h>'], 'ans' => '<stdio.h>'],
            ['q' => 'Which of the following is a bitwise AND operator in C?', 'options' => ['&', '&&', '|', '||'], 'ans' => '&'],
            ['q' => 'What is the default return value of a successful main() function in C?', 'options' => ['0', '1', '-1', 'NULL'], 'ans' => '0'],
            ['q' => 'Which escape sequence represents a newline character in C?', 'options' => ['\\n', '\\t', '\\r', '\\b'], 'ans' => '\\n'],
            ['q' => 'Which symbol is used for single-line comments in modern C (C99 and later)?', 'options' => ['//', '/*', '#', '--'], 'ans' => '//'],
            ['q' => 'Which operator has the highest precedence among the following in C?', 'options' => ['Parentheses ()', 'Addition +', 'Assignment =', 'Logical AND &&'], 'ans' => 'Parentheses ()']
        ],
        'CO2' => [
            ['q' => 'Which loop construct in C is guaranteed to execute its body at least once even if the condition is false?', 'options' => ['do-while loop', 'while loop', 'for loop', 'nested if'], 'ans' => 'do-while loop'],
            ['q' => 'What is the index of the very first element in an array in C?', 'options' => ['0', '1', '-1', 'Undefined'], 'ans' => '0'],
            ['q' => 'Which statement is used to immediately terminate a loop or switch block in C?', 'options' => ['break', 'continue', 'goto', 'return'], 'ans' => 'break'],
            ['q' => 'What happens if an array index exceeds its declared boundary in C?', 'options' => ['Undefined behavior / Memory corruption', 'Compilation error', 'Array automatically resizes', 'ArrayIndexOutOfBoundsException'], 'ans' => 'Undefined behavior / Memory corruption'],
            ['q' => 'Which control statement skips the rest of the current iteration and jumps to the next loop iteration?', 'options' => ['continue', 'break', 'skip', 'pass'], 'ans' => 'continue'],
            ['q' => 'What is the correct declaration of a 1-D integer array named "arr" with 10 elements in C?', 'options' => ['int arr[10];', 'int arr;', 'array arr[10];', 'int[10] arr;'], 'ans' => 'int arr[10];'],
            ['q' => 'In a switch-case statement, what happens if no break statement is present in a matching case?', 'options' => ['Execution falls through to the subsequent cases', 'Program crashes immediately', 'Syntax error occurs', 'Only default case executes'], 'ans' => 'Execution falls through to the subsequent cases'],
            ['q' => 'Which data type is permitted inside the switch control expression in C?', 'options' => ['Integer and Character', 'Float and Double', 'String literal', 'Pointer to struct'], 'ans' => 'Integer and Character'],
            ['q' => 'How many elements can the array float marks[3][4]; store in total?', 'options' => ['12', '7', '14', '9'], 'ans' => '12'],
            ['q' => 'Which loop is typically preferred when the exact number of iterations is known in advance?', 'options' => ['for loop', 'while loop', 'do-while loop', 'infinite loop'], 'ans' => 'for loop']
        ],
        'CO3' => [
            ['q' => 'Which operator is used to obtain the memory address of a variable in C?', 'options' => ['& (Address-of)', '* (Indirection)', '-> (Arrow)', '% (Modulus)'], 'ans' => '& (Address-of)'],
            ['q' => 'Which operator is known as the dereferencing or indirection operator in C?', 'options' => ['*', '&', '->', '.'], 'ans' => '*'],
            ['q' => 'Which special character marks the termination of a string in C?', 'options' => ['\\0 (Null character)', '\\n (Newline)', 'EOF', 'Space'], 'ans' => '\\0 (Null character)'],
            ['q' => 'Which standard library function in <string.h> is used to calculate the length of a string?', 'options' => ['strlen()', 'length()', 'strlength()', 'size()'], 'ans' => 'strlen()'],
            ['q' => 'Which function is used to copy the contents of one string to another in C?', 'options' => ['strcpy()', 'strcmp()', 'strcat()', 'strdup()'], 'ans' => 'strcpy()'],
            ['q' => 'What does a pointer variable store in C?', 'options' => ['Memory address of another variable', 'Value of a variable', 'Data type of a variable', 'Size of an array'], 'ans' => 'Memory address of another variable'],
            ['q' => 'What is the output of strcmp("apple", "apple") in C?', 'options' => ['0', '1', '-1', 'True'], 'ans' => '0'],
            ['q' => 'What is a NULL pointer in C?', 'options' => ['A pointer that points to memory address 0 / no valid location', 'A pointer pointing to an integer 0', 'An uninitialized pointer', 'A pointer that points to itself'], 'ans' => 'A pointer that points to memory address 0 / no valid location'],
            ['q' => 'If ptr points to an integer array element, what does ptr++ do?', 'options' => ['Advances ptr to the next integer element in memory', 'Increments the integer value stored at ptr by 1', 'Causes a compilation error', 'Doubles the memory address of ptr'], 'ans' => 'Advances ptr to the next integer element in memory'],
            ['q' => 'Which function concatenates (appends) source string to the end of target string in C?', 'options' => ['strcat()', 'strcpy()', 'strappend()', 'strjoin()'], 'ans' => 'strcat()']
        ],
        'CO4' => [
            ['q' => 'What is the return type of a C function that does not return any value to the caller?', 'options' => ['void', 'int', 'null', 'empty'], 'ans' => 'void'],
            ['q' => 'What is a function prototype in C?', 'options' => ['A declaration that specifies function name, return type, and parameter types', 'The complete executable body of the function', 'A function call inside main()', 'A built-in library function header'], 'ans' => 'A declaration that specifies function name, return type, and parameter types'],
            ['q' => 'When arguments are passed to a function by value, what does the called function receive?', 'options' => ['A copy of the argument values', 'Direct reference to the original variable', 'A pointer to the caller stack', 'A global variable address'], 'ans' => 'A copy of the argument values'],
            ['q' => 'What is recursion in C programming?', 'options' => ['A function calling itself directly or indirectly', 'A loop running indefinitely', 'Passing pointers between functions', 'Declaring functions inside structures'], 'ans' => 'A function calling itself directly or indirectly'],
            ['q' => 'Which storage class keyword preserves a variable value across multiple function calls?', 'options' => ['static', 'auto', 'register', 'extern'], 'ans' => 'static'],
            ['q' => 'What is the default scope of a variable declared inside a C function without keywords?', 'options' => ['Local to that function', 'Global across all functions', 'Static across function calls', 'Visible to all source files'], 'ans' => 'Local to that function'],
            ['q' => 'How can a C function modify variables defined in its calling function?', 'options' => ['By passing pointers (call by reference)', 'By passing arguments by value', 'By using the const keyword', 'Functions can never affect caller variables'], 'ans' => 'By passing pointers (call by reference)'],
            ['q' => 'Which standard library function in <stdlib.h> allocates dynamic memory on the heap?', 'options' => ['malloc()', 'alloc()', 'new', 'create()'], 'ans' => 'malloc()'],
            ['q' => 'What is required in every recursive function to prevent an infinite recursive loop and stack overflow?', 'options' => ['A base case (termination condition)', 'A while loop', 'A global counter', 'A goto statement'], 'ans' => 'A base case (termination condition)'],
            ['q' => 'Which keyword is used to access a global variable defined in another file in C?', 'options' => ['extern', 'static', 'register', 'global'], 'ans' => 'extern']
        ]
    ];

    /**
     * Resolve Course Outcome Description by querying course_files, sibling batches, syllabus_registry, or lesson plans.
     */
    private function resolveCoDescription($subjectId, $subjectCode, $subjectName, $co)
    {
        // 1. Direct course_files for this batch_subject_id
        $syllabus = DB::table('course_files')->where('batch_subject_id', $subjectId)->first();
        if ($syllabus && !empty($syllabus->parsed_cos)) {
            $parsedCos = is_string($syllabus->parsed_cos) ? json_decode($syllabus->parsed_cos, true) : $syllabus->parsed_cos;
            if (is_array($parsedCos)) {
                foreach ($parsedCos as $c) {
                    if (isset($c['id']) && strcasecmp(trim($c['id']), trim($co)) === 0 && !empty($c['description'])) {
                        return $c['description'];
                    }
                }
            }
        }

        // 2. Sibling batch_subjects with same subject_code
        $siblingIds = DB::table('batch_subjects')->where('subject_code', $subjectCode)->pluck('id');
        if ($siblingIds->isNotEmpty()) {
            $siblingSyllabus = DB::table('course_files')->whereIn('batch_subject_id', $siblingIds)->whereNotNull('parsed_cos')->first();
            if ($siblingSyllabus && !empty($siblingSyllabus->parsed_cos)) {
                $parsedCos = is_string($siblingSyllabus->parsed_cos) ? json_decode($siblingSyllabus->parsed_cos, true) : $siblingSyllabus->parsed_cos;
                if (is_array($parsedCos)) {
                    foreach ($parsedCos as $c) {
                        if (isset($c['id']) && strcasecmp(trim($c['id']), trim($co)) === 0 && !empty($c['description'])) {
                            return $c['description'];
                        }
                    }
                }
            }
        }

        // 3. Syllabus registry
        $reg = DB::table('syllabus_registry')->where('subject_code', $subjectCode)->first();
        if ($reg && !empty($reg->course_outcomes)) {
            $regCos = is_string($reg->course_outcomes) ? json_decode($reg->course_outcomes, true) : $reg->course_outcomes;
            if (is_array($regCos)) {
                foreach ($regCos as $c) {
                    $cId = $c['co'] ?? ($c['id'] ?? '');
                    if (strcasecmp(trim($cId), trim($co)) === 0 && !empty($c['description'])) {
                        return $c['description'];
                    }
                }
            }
        }

        // 4. Lesson plans or templates
        $topics = DB::table('lesson_plans')
            ->where('batch_subject_id', $subjectId)
            ->where('co_id', $co)
            ->pluck('topic_content')
            ->toArray();
        if (empty($topics)) {
            $topics = DB::table('lesson_plan_templates')
                ->where('subject_code', $subjectCode)
                ->where('co_id', $co)
                ->pluck('topic_content')
                ->toArray();
        }
        if (!empty($topics)) {
            return implode(', ', array_slice($topics, 0, 5));
        }

        return "Core concepts and syllabus topics of {$subjectName} for outcome {$co}";
    }

    /**
     * Get subject-safe fallback question pool (never serves Embedded Systems to non-embedded courses).
     */
    private function getSubjectFallbackPool($subjectCode, $subjectName, $co, $coDesc)
    {
        $sNameLower = strtolower($subjectName);
        $sCodeClean = strtoupper(str_replace([' ', '-'], '', $subjectCode));

        // Course 3045 or C Programming courses
        if (str_contains($sNameLower, 'c prog') || str_contains($sNameLower, 'fundamentals of c') || str_contains($sCodeClean, '3045')) {
            if (isset($this->cProgrammingMCQs[$co])) {
                return $this->cProgrammingMCQs[$co];
            }
            return array_merge($this->cProgrammingMCQs['CO1'], $this->cProgrammingMCQs['CO2']);
        }

        // Embedded Systems courses
        if (str_contains($sNameLower, 'embedded') || str_contains($sCodeClean, '5041')) {
            if (isset($this->embeddedSystemsMCQs[$co])) {
                return $this->embeddedSystemsMCQs[$co];
            }
            return array_merge($this->embeddedSystemsMCQs['CO1'], $this->embeddedSystemsMCQs['CO2']);
        }

        // Dynamic subject-bound fallback for any other course
        return [
            [
                'q' => "Which of the following best describes the fundamental principle of {$coDesc} in {$subjectName}?",
                'options' => [
                    "Standard theoretical concept and application in {$subjectName}",
                    "Unrelated computational procedure",
                    "Random external protocol",
                    "Obsolete non-standard method"
                ],
                'ans' => "Standard theoretical concept and application in {$subjectName}"
            ],
            [
                'q' => "In the context of {$subjectName} ({$co}), what is the primary objective of studying {$coDesc}?",
                'options' => [
                    "To apply core methodologies and solve domain problems",
                    "To ignore system constraints",
                    "To bypass standard analysis",
                    "None of the above"
                ],
                'ans' => "To apply core methodologies and solve domain problems"
            ],
            [
                'q' => "Which characteristic is essential when analyzing {$coDesc} in {$subjectName}?",
                'options' => [
                    "Adherence to domain specifications and accuracy",
                    "Arbitrary parameter estimation",
                    "Zero verification and testing",
                    "Inconsistent execution steps"
                ],
                'ans' => "Adherence to domain specifications and accuracy"
            ],
            [
                'q' => "What is the recommended analytical approach for {$coDesc} in {$subjectName}?",
                'options' => [
                    "Systematic evaluation according to engineering standards",
                    "Ad-hoc guesswork",
                    "Omitting core requirements",
                    "Uncontrolled testing"
                ],
                'ans' => "Systematic evaluation according to engineering standards"
            ]
        ];
    }

    /**
     * Generate MCQ questions payload using Question Bank, Gemini AI, or Subject-Safe Fallbacks.
     */
    public function generateQuestionsPayload($subjectId, array $cos, $qCount, $generationMode = 'bank', $genAnswers = 1)
    {
        $batchSubject = DB::table('batch_subjects')->where('id', $subjectId)->first();
        if (!$batchSubject) return [];

        $subjectCode = trim((string)$batchSubject->subject_code);
        $subjectName = trim((string)($batchSubject->subject_name ?? 'Course'));
        $revision = trim((string)($batchSubject->syllabus_revision_code ?? 'REV2021'));

        $numCos = count($cos);
        $qCountPerCo = ceil($qCount / max(1, $numCos));

        $payload = [];
        $totalMcq = 0;

        foreach ($cos as $co) {
            if ($totalMcq >= $qCount) break;

            $remaining = $qCount - $totalMcq;
            $currentLimit = min($remaining, $qCountPerCo);

            $dbQuestions = collect();
            if ($generationMode === 'bank') {
                $dbQuestions = DB::table('question_bank')
                    ->where('subject_code', $subjectCode)
                    ->where('co_tag', $co)
                    ->where('type', 'MCQ')
                    ->inRandomOrder()
                    ->get();
            }

            if ($dbQuestions->isNotEmpty()) {
                $dbCount = count($dbQuestions);
                $limitToUse = min($currentLimit, $dbCount);
                for ($i = 0; $i < $limitToUse; $i++) {
                    $q = $dbQuestions[$i];
                    $optionsArr = is_string($q->options) ? json_decode($q->options, true) : $q->options;

                    $correctAnsVal = $q->correct_answer;
                    if (is_array($optionsArr) && in_array(strtoupper(trim((string)$q->correct_answer)), ['A', 'B', 'C', 'D'])) {
                        $charMap = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
                        $idx = $charMap[strtoupper(trim((string)$q->correct_answer))];
                        if (isset($optionsArr[$idx])) {
                            $correctAnsVal = $optionsArr[$idx];
                        }
                    }

                    $payload[] = [
                        'q' => $q->question_text,
                        'options' => $optionsArr ?: [],
                        'ans' => $genAnswers ? $correctAnsVal : null,
                        'co' => $co
                    ];
                    $totalMcq++;
                }
            } else {
                $coDesc = $this->resolveCoDescription($subjectId, $subjectCode, $subjectName, $co);

                $apiKey = env('GEMINI_API_KEY');
                $generatedWithAi = false;

                if ($apiKey && \App\Http\Controllers\SystemSettingController::isAiEnabled()) {
                    try {
                        $prompt = "You are a university engineering professor and examiner for the course '{$subjectName}' (Course Code: {$subjectCode}, Revision: {$revision}).
Generate exactly {$currentLimit} multiple-choice questions (MCQs) for Course Outcome '{$co}' strictly focusing on the course syllabus topic: '{$coDesc}'.

STRICT REQUIREMENTS:
1. Every question and all four options MUST be 100% strictly relevant to '{$subjectName}' (Course Code: {$subjectCode}).
2. DO NOT include questions from unrelated subjects such as Embedded Systems, microcontrollers, or general electronics unless '{$subjectName}' is specifically that topic. If the course is 'Fundamentals of C Programming', all questions MUST be strictly about C programming (syntax, data types, control flow, functions, pointers, arrays, memory, standard I/O).
3. Provide exactly 4 distinct, plausible options per question (Option A, Option B, Option C, Option D).
4. The 'ans' field must be the EXACT string of one of the 4 options.
5. Return ONLY a valid JSON array of objects strictly matching this schema:
[
  {
    \"q\": \"Question text?\",
    \"options\": [\"Option A\", \"Option B\", \"Option C\", \"Option D\"],
    \"ans\": \"Exact text of the correct option\"
  }
]";

                        $response = \Illuminate\Support\Facades\Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                            'contents' => [['parts' => [['text' => $prompt]]]],
                            'generationConfig' => ['responseMimeType' => 'application/json']
                        ]);

                        if ($response->successful()) {
                            $jsonString = $response->json('candidates.0.content.parts.0.text');
                            $cleanJson = trim(str_replace(['```json', '```JSON', '```'], '', $jsonString));
                            $parsed = json_decode($cleanJson, true);

                            if (is_array($parsed) && count($parsed) > 0) {
                                foreach ($parsed as $q) {
                                    if ($totalMcq >= $qCount) break;
                                    if (isset($q['q']) && isset($q['options']) && isset($q['ans']) && is_array($q['options'])) {
                                        $q['co'] = $co;
                                        if (!$genAnswers) $q['ans'] = null;
                                        $payload[] = $q;
                                        $totalMcq++;
                                    }
                                }
                                if ($totalMcq > 0) {
                                    $generatedWithAi = true;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::warning("Gemini MCQ generation failed for {$subjectCode}: " . $e->getMessage());
                    }
                }

                if (!$generatedWithAi) {
                    $fallbackPool = $this->getSubjectFallbackPool($subjectCode, $subjectName, $co, $coDesc);
                    shuffle($fallbackPool);
                    $poolSize = count($fallbackPool);
                    $added = 0;
                    while ($added < $currentLimit && $totalMcq < $qCount && $poolSize > 0) {
                        $q = $fallbackPool[$added % $poolSize];
                        $q['co'] = $co;
                        if (!$genAnswers) {
                            $q['ans'] = null;
                        }
                        $payload[] = $q;
                        $totalMcq++;
                        $added++;
                    }
                }
            }
        }

        return $payload;
    }

    // Lecturer: Preview & Edit Questions before publish
    public function previewOnlineTestQuestions(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Lecturer', 'HOD', 'Principal', 'Admin', 'Super_Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        $batchSubject = DB::table('batch_subjects')->where('id', $subjectId)->first();
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject assignment not found.']);
        }

        $cos = $request->input('cos', []);
        $qCount = intval($request->input('q_count', 5));
        $generationMode = $request->input('generation_mode', 'ai');
        $genAnswers = intval($request->input('gen_answers', 1));

        if (empty($cos)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Please select at least one CO.']);
        }

        $questions = $this->generateQuestionsPayload($subjectId, $cos, $qCount, $generationMode, $genAnswers);

        return response()->json([
            'status' => 'SUCCESS',
            'subject_code' => $batchSubject->subject_code,
            'subject_name' => $batchSubject->subject_name,
            'questions' => $questions
        ]);
    }

    // Lecturer: Publish a new Online Test
    public function publishOnlineTest(Request $request, $subjectId)
    {
        $role = Session::get('userRole');
        if (!in_array($role, ['Lecturer', 'HOD', 'Principal', 'Admin', 'Super_Admin'])) {
            return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);
        }

        // classroom_id and subject_code are required from batch_subjects
        $batchSubject = DB::table('batch_subjects')->where('id', $subjectId)->first();
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject assignment not found.']);
        }
        $classroomId = $batchSubject->classroom_id;
        $subjectCode = $batchSubject->subject_code;

        $cos = $request->input('cos', []);
        $attempts = $request->input('attempts', 1);
        $duration = $request->input('duration', 30);
        $start = $request->input('start');
        $end = $request->input('end');
        $qCount = intval($request->input('q_count', 3));
        $genAnswers = intval($request->input('gen_answers', 1));
        $generationMode = $request->input('generation_mode', 'bank');

        if (empty($cos)) {
            return response()->json(['status' => 'ERROR', 'message' => 'Please select at least one CO.']);
        }

        $payload = [];
        $totalMcq = 0;

        // If reviewed/edited questions are submitted directly from frontend preview
        $reviewedQuestions = $request->input('questions');
        if (is_array($reviewedQuestions) && count($reviewedQuestions) > 0) {
            foreach ($reviewedQuestions as $item) {
                if (empty(trim($item['q'] ?? ''))) continue;
                $options = isset($item['options']) && is_array($item['options'])
                    ? array_values(array_filter(array_map('trim', $item['options']), fn($o) => $o !== ''))
                    : [];
                if (count($options) < 2) continue;

                $ans = isset($item['ans']) ? trim((string)$item['ans']) : null;
                $coTag = !empty($item['co']) ? trim($item['co']) : ($cos[0] ?? 'CO1');

                $payload[] = [
                    'q' => trim($item['q']),
                    'options' => $options,
                    'ans' => $genAnswers ? $ans : null,
                    'co' => $coTag
                ];
                $totalMcq++;
            }

            if ($totalMcq === 0) {
                return response()->json(['status' => 'ERROR', 'message' => 'No valid questions found in submission. Each question must have question text and at least 2 options.']);
            }
        } else {
            // Direct generation path (Instant Publish)
            $payload = $this->generateQuestionsPayload($subjectId, $cos, $qCount, $generationMode, $genAnswers);
            $totalMcq = count($payload);
        }

        $customName = $request->input('custom_name');
        $testName = !empty($customName) ? trim($customName) : 'Online MCQ Test - ' . implode(', ', $cos);

        $newTestId = (string) Str::uuid();

        // Save to test_configs
        DB::table('test_configs')->insert([
            'test_id' => $newTestId,
            'subject_code' => $subjectCode,
            'classroom_id' => $classroomId,
            'test_name' => $testName,
            'start_time' => $start ?: now(),
            'end_time' => $end,
            'duration' => $duration,
            'selected_cos' => json_encode($cos),
            'mcq_count' => $totalMcq,
            'target_percentage' => 50,
            'pass_threshold' => 40,
            'is_active' => true,
            'max_attempts' => $attempts,
            'is_auto_scheduled' => $start ? true : false,
            'questions_payload' => json_encode($payload),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Dispatch Web Push Notification to students of this classroom
        try {
            $studentIds = DB::table('students')->where('classroom_id', $classroomId)->pluck('reg_no')->toArray();
            if (!empty($studentIds)) {
                $pushTitle = "📝 Online MCQ Test: " . $subjectCode;
                $pushBody = "{$testName} ({$totalMcq} MCQs, {$duration} mins) has been published. Tap to launch!";
                $launchUrl = "/student/online-tests/{$newTestId}/start";

                $subscriptions = \App\Models\PushSubscription::whereIn('user_id', $studentIds)->get();
                if ($subscriptions->isNotEmpty()) {
                    PushNotificationService::dispatchPayload(
                        $subscriptions,
                        $pushTitle,
                        $pushBody,
                        $launchUrl,
                        'carmel-mcq-' . $newTestId
                    );
                }
            }
        } catch (\Exception $e) {
            Log::warning("MCQ Push Notification dispatch failed: " . $e->getMessage());
        }

        return response()->json(['status' => 'SUCCESS', 'message' => 'Online Test Published successfully.', 'mcq_count' => $totalMcq, 'test_id' => $newTestId]);
    }

    // Lecturer: Get Active Online Tests
    public function getActiveTestsLecturer(Request $request, $subjectId)
    {
        $batchSubject = DB::table('batch_subjects')->where('id', $subjectId)->first();
        if (!$batchSubject) {
            return response()->json(['status' => 'ERROR', 'message' => 'Subject assignment not found.']);
        }
        $subjectCode = $batchSubject->subject_code;

        $tests = DB::table('test_configs')
            ->where('subject_code', $subjectCode)
            ->where('classroom_id', $batchSubject->classroom_id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($tests as $t) {
            $t->student_count = DB::table('test_attempts')->where('test_id', $t->test_id)->distinct('reg_no')->count('reg_no');
            $t->completed_count = DB::table('test_attempts')->where('test_id', $t->test_id)->where('status', 'completed')->count();
        }

        return response()->json(['status' => 'SUCCESS', 'data' => $tests]);
    }

    // Lecturer: Generate PDF Data Report
    public function generateTestReport(Request $request, $testId)
    {
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        // Get attempts (best score per student)
        $attempts = DB::table('test_attempts')
            ->join('students', 'test_attempts.reg_no', '=', 'students.reg_no')
            ->where('test_attempts.test_id', $testId)
            ->where('test_attempts.status', 'completed')
            ->select('students.reg_no', 'students.name', 'test_attempts.total_score', 'test_attempts.start_time', 'test_attempts.end_time', 'test_attempts.attempt_number')
            ->orderBy('test_attempts.total_score', 'desc')
            ->get();

        // Get additional subject/classroom info if possible
        $subjectInfo = DB::table('batch_subjects')
            ->leftJoin('class_management', 'batch_subjects.classroom_id', '=', 'class_management.classroom_id')
            ->leftJoin('r26_class_management', 'batch_subjects.classroom_id', '=', 'r26_class_management.classroom_id')
            ->where('batch_subjects.subject_code', $test->subject_code)
            ->select('batch_subjects.semester', DB::raw("COALESCE(class_management.branch, r26_class_management.branch) as branch"))
            ->first();

        return response()->json([
            'status' => 'SUCCESS',
            'test_info' => $test,
            'meta' => [
                'lecturer_name' => Session::get('userName', 'Lecturer'),
                'semester' => $subjectInfo ? $subjectInfo->semester : 'N/A',
                'department' => $subjectInfo ? $subjectInfo->branch : 'N/A'
            ],
            'report' => $attempts
        ]);
    }

    // Student: Get Available Tests
    public function getAvailableTests(Request $request)
    {
        $regNo = Session::get('userId');
        $student = DB::table('students')->where('reg_no', $regNo)->first();
        if (!$student) return response()->json(['status' => 'ERROR', 'message' => 'Student not found']);
        
        $tests = DB::table('test_configs')
            ->where('classroom_id', $student->classroom_id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $completedTestsCount = DB::table('test_attempts')
            ->where('reg_no', $regNo)
            ->where('status', 'completed')
            ->distinct('test_id')
            ->count('test_id');

        $activeTests = [];
        $stats = [
            'online_tests_active' => 0,
            'online_tests_submitted' => $completedTestsCount
        ];

        foreach ($tests as $t) {
            $subj = DB::table('batch_subjects')->where('subject_code', $t->subject_code)->first();
            $t->subject_name = $subj ? $subj->subject_name : $t->subject_code;

            $attemptsCount = DB::table('test_attempts')
                ->where('test_id', $t->test_id)
                ->where('reg_no', $regNo)
                ->count();
            
            $hasCompleted = DB::table('test_attempts')
                ->where('test_id', $t->test_id)
                ->where('reg_no', $regNo)
                ->where('status', 'completed')
                ->exists();

            $t->my_attempts = $attemptsCount;
            $t->can_take = ($attemptsCount < $t->max_attempts && !$hasCompleted);

            $now = now();
            if ($t->start_time && $now < $t->start_time) {
                $t->can_take = false;
                $t->status_message = 'Starts at ' . \Carbon\Carbon::parse($t->start_time)->format('M d, h:i A');
            } elseif ($t->end_time && $now > $t->end_time) {
                $t->can_take = false; // Overdue
                $t->status_message = 'Expired';
            } else {
                $t->status_message = 'Active';
            }

            if (!$hasCompleted && $attemptsCount < $t->max_attempts) {
                // If it's not expired
                if ($t->status_message !== 'Expired') {
                    $stats['online_tests_active']++;
                    $activeTests[] = $t;
                }
            }
        }

        return response()->json([
            'status' => 'SUCCESS', 
            'tests' => $activeTests,
            'stats' => $stats
        ]);
    }

    /**
     * Student: Render the dedicated mobile/desktop online test taking interface.
     */
    public function showOnlineTest(Request $request, $testId)
    {
        $regNo = Session::get('userId');
        $userRole = Session::get('userRole');

        if (!$regNo || $userRole !== 'Student') {
            Session::put('url.intended', url("/student/online-tests/{$testId}/start"));
            return redirect('/')->with('error', 'Please log in with your student credentials to access the test.');
        }

        $student = DB::table('students')->where('reg_no', $regNo)->first();
        if (!$student) {
            return response()->view('student_online_test_error', [
                'title' => 'Student Profile Not Found',
                'message' => 'Unable to locate your student profile in the system.',
                'backUrl' => '/dashboard/student'
            ], 404);
        }

        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) {
            // Check fallback by integer id
            $test = DB::table('test_configs')->where('id', $testId)->first();
        }

        if (!$test) {
            return response()->view('student_online_test_error', [
                'title' => 'Online Test Not Found',
                'message' => 'The requested test configuration could not be found. It may have been deleted or expired.',
                'backUrl' => '/dashboard/student'
            ], 404);
        }

        // Verify classroom batch assignment
        if ($test->classroom_id && $student->classroom_id !== $test->classroom_id) {
            return response()->view('student_online_test_error', [
                'title' => 'Test Not Assigned to Your Batch',
                'message' => 'This examination has been scheduled for a different classroom batch.',
                'backUrl' => '/dashboard/student'
            ], 403);
        }

        // Verify active status
        if (!$test->is_active) {
            return response()->view('student_online_test_error', [
                'title' => 'Test Inactive',
                'message' => 'This test is currently inactive or deactivated by the subject faculty.',
                'backUrl' => '/dashboard/student'
            ]);
        }

        // Check start and end schedule
        $now = now();
        if ($test->start_time && $now < Carbon::parse($test->start_time)) {
            return response()->view('student_online_test_error', [
                'title' => 'Test Not Started Yet',
                'message' => 'This test is scheduled to commence on ' . Carbon::parse($test->start_time)->format('d M Y, h:i A') . '. Please check back at the scheduled start time.',
                'backUrl' => '/dashboard/student'
            ]);
        }

        if ($test->end_time && $now > Carbon::parse($test->end_time)) {
            return response()->view('student_online_test_error', [
                'title' => 'Test Deadline Expired',
                'message' => 'The submission deadline for this test was ' . Carbon::parse($test->end_time)->format('d M Y, h:i A') . '.',
                'backUrl' => '/dashboard/student'
            ]);
        }

        // Attempts check
        $attemptsCount = DB::table('test_attempts')
            ->where('test_id', $test->test_id)
            ->where('reg_no', $regNo)
            ->where('status', 'completed')
            ->count();

        $activeAttempt = DB::table('test_attempts')
            ->where('test_id', $test->test_id)
            ->where('reg_no', $regNo)
            ->where('status', 'in_progress')
            ->orderBy('start_time', 'desc')
            ->first();

        if ($attemptsCount >= ($test->max_attempts ?? 1) && !$activeAttempt) {
            $bestScore = DB::table('test_attempts')
                ->where('test_id', $test->test_id)
                ->where('reg_no', $regNo)
                ->max('total_score');

            return response()->view('student_online_test_error', [
                'title' => 'Maximum Attempts Completed',
                'message' => "You have already completed all permitted attempts ({$test->max_attempts}) for this test. Your best score: {$bestScore} / {$test->mcq_count}.",
                'backUrl' => '/dashboard/student'
            ]);
        }

        // Resolve subject details
        $batchSubject = DB::table('batch_subjects')
            ->where('subject_code', $test->subject_code)
            ->where('classroom_id', $test->classroom_id)
            ->first();

        $subjectName = $batchSubject?->subject_name ?? $test->subject_code;

        return view('student_online_test', compact(
            'test',
            'student',
            'subjectName',
            'attemptsCount',
            'activeAttempt'
        ));
    }

    /**
     * Helper to shuffle questions and their options for a participant.
     */
    private function shuffleQuestionsAndOptions(array $questions): array
    {
        $shuffled = $questions;
        shuffle($shuffled);

        foreach ($shuffled as &$q) {
            if (isset($q['options']) && is_array($q['options'])) {
                $options = array_values(array_map('trim', $q['options']));

                // If ans is a letter 'A','B','C','D' and not the exact option text, resolve it before shuffle
                if (isset($q['ans']) && $q['ans'] !== null) {
                    $trimmedAns = trim((string)$q['ans']);
                    if (in_array(strtoupper($trimmedAns), ['A', 'B', 'C', 'D'])) {
                        $charMap = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
                        $letterIdx = $charMap[strtoupper($trimmedAns)];
                        if (!in_array($trimmedAns, $options) && isset($options[$letterIdx])) {
                            $q['ans'] = $options[$letterIdx];
                        }
                    }
                }

                shuffle($options);
                $q['options'] = $options;
            }
        }
        unset($q);

        return $shuffled;
    }

    /**
     * Resolve the questions payload specific to a student attempt.
     */
    private function resolveAttemptQuestions($attempt, $test): array
    {
        if ($attempt) {
            if (isset($attempt->questions_payload) && !empty($attempt->questions_payload)) {
                $decoded = json_decode($attempt->questions_payload, true);
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }

            if (!empty($attempt->responses)) {
                $respData = json_decode($attempt->responses, true);
                if (is_array($respData) && isset($respData['__shuffled_questions']) && is_array($respData['__shuffled_questions'])) {
                    return $respData['__shuffled_questions'];
                }
            }
        }

        return json_decode($test->questions_payload, true) ?: [];
    }

    // Student: Start Test
    public function startTest(Request $request, $testId)
    {
        $regNo = Session::get('userId');
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        // Check if there is already an in-progress attempt to resume
        $activeAttempt = DB::table('test_attempts')
            ->where('test_id', $testId)
            ->where('reg_no', $regNo)
            ->where('status', 'in_progress')
            ->orderBy('start_time', 'desc')
            ->first();

        if ($activeAttempt) {
            $fullPayload = $this->resolveAttemptQuestions($activeAttempt, $test);
            $safePayload = array_map(function($q) {
                unset($q['ans']);
                return $q;
            }, $fullPayload);

            $elapsedMinutes = Carbon::parse($activeAttempt->start_time)->diffInMinutes(now());
            $remainingMinutes = max(1, $test->duration - $elapsedMinutes);

            return response()->json([
                'status' => 'SUCCESS', 
                'attempt_id' => $activeAttempt->attempt_id ?? $activeAttempt->id,
                'duration' => $remainingMinutes,
                'questions' => $safePayload
            ]);
        }

        $attemptsCount = DB::table('test_attempts')->where('test_id', $testId)->where('reg_no', $regNo)->count();
        if ($attemptsCount >= $test->max_attempts) {
            return response()->json(['status' => 'ERROR', 'message' => 'Maximum attempts reached.']);
        }

        $masterPayload = json_decode($test->questions_payload, true) ?: [];
        $shuffledPayload = $this->shuffleQuestionsAndOptions($masterPayload);

        $attemptUuid = (string) Str::uuid();
        $insertData = [
            'attempt_id' => $attemptUuid,
            'reg_no' => $regNo,
            'test_id' => $testId,
            'attempt_number' => $attemptsCount + 1,
            'start_time' => now(),
            'status' => 'in_progress',
            'questions_payload' => json_encode($shuffledPayload),
            'created_at' => now(),
            'updated_at' => now()
        ];

        try {
            DB::table('test_attempts')->insert($insertData);
        } catch (\Exception $e) {
            // Fallback in case questions_payload column is not yet present on table
            unset($insertData['questions_payload']);
            $insertData['responses'] = json_encode(['__shuffled_questions' => $shuffledPayload]);
            DB::table('test_attempts')->insert($insertData);
        }

        // Strip answers before sending to client
        $safePayload = array_map(function($q) {
            unset($q['ans']);
            return $q;
        }, $shuffledPayload);

        return response()->json([
            'status' => 'SUCCESS', 
            'attempt_id' => $attemptUuid,
            'duration' => $test->duration,
            'questions' => $safePayload
        ]);
    }

    // Student: Submit Test
    public function submitTest(Request $request, $testId)
    {
        $regNo = Session::get('userId');
        $answers = $request->input('answers', []); // ['0' => 'option A', '1' => 'option B'] array by index
        
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        // Find active attempt
        $attempt = DB::table('test_attempts')->where('test_id', $testId)->where('reg_no', $regNo)->where('status', 'in_progress')->orderBy('start_time', 'desc')->first();
        if (!$attempt) return response()->json(['status' => 'ERROR', 'message' => 'No active attempt found.']);

        $payload = $this->resolveAttemptQuestions($attempt, $test);
        $score = 0;
        $total = count($payload);
        $results = []; // Detailed results for summary

        $coScores = [];

        foreach ($payload as $index => $q) {
            $co = $q['co'] ?? 'CO1';
            if (!isset($coScores[$co])) {
                $coScores[$co] = ['max' => 0, 'obtained' => 0];
            }
            $coScores[$co]['max'] += 1;

            $studentAns = isset($answers[$index]) ? trim((string)$answers[$index]) : null;
            $correctAns = $q['ans'] !== null ? trim((string)$q['ans']) : null;
            $isCorrect = ($correctAns !== null && strcasecmp($studentAns, $correctAns) === 0);
            if ($isCorrect) {
                $score++;
                $coScores[$co]['obtained'] += 1;
            }

            $results[] = [
                'q' => $q['q'],
                'student_ans' => $studentAns,
                'correct_ans' => $q['ans'],
                'is_correct' => $isCorrect,
                'co' => $co
            ];
        }

        // Update attempt
        $updateData = [
            'end_time' => now(),
            'total_score' => $score,
            'status' => 'completed',
            'updated_at' => now()
        ];

        if (isset($attempt->questions_payload)) {
            $updateData['responses'] = json_encode($answers);
        } else {
            // Keep shuffled questions alongside responses in fallback mode
            $updateData['responses'] = json_encode([
                '__shuffled_questions' => $payload,
                'answers' => $answers
            ]);
        }

        DB::table('test_attempts')->where('attempt_id', $attempt->attempt_id)->update($updateData);

        // Sync to academic_marks (Keep highest mark)
        foreach ($coScores as $co => $data) {
            $existing = DB::table('academic_marks')->where([
                'reg_no' => $regNo,
                'subject_code' => $test->subject_code,
                'category' => 'Online Test',
                'co_tag' => $co
            ])->first();
            
            if (!$existing || $data['obtained'] > $existing->marks_obtained) {
                DB::table('academic_marks')->updateOrInsert([
                    'reg_no' => $regNo,
                    'subject_code' => $test->subject_code,
                    'category' => 'Online Test',
                    'co_tag' => $co
                ], [
                    'max_marks' => $data['max'],
                    'marks_obtained' => $data['obtained'],
                    'entered_by' => null,
                    'updated_at' => now()
                ]);
            }
        }

        // Only show correct answers and details if the test has ended
        $showAnswers = false;
        if (!$test->end_time) {
            $showAnswers = true;
        } else {
            $showAnswers = (now() >= $test->end_time);
        }

        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Test submitted successfully.',
            'summary' => [
                'score' => $score,
                'total' => $total,
                'percentage' => $total > 0 ? round(($score / $total) * 100, 2) : 0,
                'details' => $showAnswers ? $results : null,
                'message' => $showAnswers ? null : 'Answers will be available after the test end time: ' . $test->end_time
            ]
        ]);
    }

    // Student: View Answer Key (Only after test ends)
    public function getAnswerKey(Request $request, $testId)
    {
        $regNo = Session::get('userId');
        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) {
            return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);
        }

        // Enforce: must have ended
        $now = now();
        if ($test->end_time && $now < $test->end_time) {
            return response()->json([
                'status' => 'ERROR', 
                'message' => 'Answer key is only available after the test end time: ' . $test->end_time
            ]);
        }

        // Must have completed at least one attempt
        $attempt = DB::table('test_attempts')
            ->where('test_id', $testId)
            ->where('reg_no', $regNo)
            ->where('status', 'completed')
            ->orderBy('total_score', 'desc') // show best attempt
            ->first();

        if (!$attempt) {
            return response()->json([
                'status' => 'ERROR', 
                'message' => 'You must complete the test first to view the answer key.'
            ]);
        }

        $payload = $this->resolveAttemptQuestions($attempt, $test);

        $decodedResponses = json_decode($attempt->responses, true) ?: [];
        $studentAnswers = $decodedResponses;
        if (is_array($decodedResponses) && isset($decodedResponses['__shuffled_questions'])) {
            $studentAnswers = $decodedResponses['answers'] ?? [];
        }

        $results = [];
        foreach ($payload as $index => $q) {
            $studentAns = isset($studentAnswers[$index]) ? trim((string)$studentAnswers[$index]) : null;
            $correctAns = $q['ans'] !== null ? trim((string)$q['ans']) : null;
            $isCorrect = ($correctAns !== null && strcasecmp($studentAns, $correctAns) === 0);
            $results[] = [
                'q' => $q['q'],
                'options' => $q['options'] ?? [],
                'student_ans' => $studentAns,
                'correct_ans' => $q['ans'],
                'is_correct' => $isCorrect,
                'co' => $q['co'] ?? 'CO1'
            ];
        }

        $totalCount = count($payload);
        return response()->json([
            'status' => 'SUCCESS',
            'test_name' => $test->test_name,
            'score' => $attempt->total_score,
            'total' => $totalCount,
            'percentage' => $totalCount > 0 ? round(($attempt->total_score / $totalCount) * 100, 2) : 0,
            'details' => $results
        ]);
    }

    // Lecturer: Delete an Online Test
    public function deleteOnlineTest(Request $request, $testId)
    {
        $role = Session::get('userRole');
        if ($role !== 'Lecturer') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);

        DB::table('test_attempts')->where('test_id', $testId)->delete();
        $deleted = DB::table('test_configs')->where('test_id', $testId)->delete();
        if ($deleted) {
            return response()->json(['status' => 'SUCCESS', 'message' => 'Online Test deleted successfully.']);
        }

        return response()->json(['status' => 'ERROR', 'message' => 'Test not found or already deleted.']);
    }

    // Lecturer: View Answer Key (no restrictions)
    public function getLecturerAnswerKey(Request $request, $testId)
    {
        $role = Session::get('userRole');
        if ($role !== 'Lecturer') return response()->json(['status' => 'ERROR', 'message' => 'Unauthorized'], 403);

        $test = DB::table('test_configs')->where('test_id', $testId)->first();
        if (!$test) return response()->json(['status' => 'ERROR', 'message' => 'Test not found']);

        $payload = json_decode($test->questions_payload, true);
        
        $results = [];
        foreach ($payload as $index => $q) {
            $results[] = [
                'q' => $q['q'],
                'options' => $q['options'],
                'correct_ans' => $q['ans'],
                'co' => $q['co']
            ];
        }

        return response()->json([
            'status' => 'SUCCESS',
            'test_name' => $test->test_name,
            'total' => count($payload),
            'details' => $results
        ]);
    }
}
