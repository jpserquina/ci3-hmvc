<?php
class MY_Output extends CI_Output
{
    function __construct()
    {
        parent::__construct();
    }

    // Overwrite the output
    public function compress_output(){
        ini_set("pcre.recursion_limit", "16777");
        $CI =& get_instance();
        $buffer = $CI->output->get_output();

        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        $new_buffer = preg_replace($re, " ", $buffer);

        // We are going to check if processing has working
        if ($new_buffer === null)
        {
            $new_buffer = $buffer;
        }

        $CI->output->set_output($new_buffer);
        $CI->output->_display();
    }

    public function compress_output2()
    {
//        $content = $this->get_output();
//
//        // do stuff to $content here
//
//        $this->set_output($content);
//        $this->_display();

        $buffer = $this->get_output();

        $search = array(
            '/\n/',			// replace end of line by a space
            '/\>[^\S ]+/s',		// strip whitespaces after tags, except space
            '/[^\S ]+\</s',		// strip whitespaces before tags, except space
            '/(\s)+/s'		// shorten multiple whitespace sequences
        );

        $replace = array(
            ' ',
            '>',
            '<',
            '\\1'
        );

        $buffer = preg_replace($search, $replace, $buffer);

        $this->set_output($buffer);
        $this->_display();
    }
}