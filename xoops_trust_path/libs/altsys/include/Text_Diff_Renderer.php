<?php
/**
 * A class to render Diffs in different formats.
 *
 * This class renders the diff in classic diff format. It is intended that
 * this class be customized via inheritance, to obtain fancier outputs.
 *
 * $Horde: framework/Text_Diff/Diff/Renderer.php,v 1.9 2005/05/04 20:21:52 chuck Exp $
 *
 * @package Text_Diff
 */
class Text_Diff_Renderer
{

    /**
     * Number of leading context "lines" to preserve.
     *
     * This should be left at zero for this class, but subclasses may want to
     * set this to other values.
     */
    public $_leading_context_lines = 0;

    /**
     * Number of trailing context "lines" to preserve.
     *
     * This should be left at zero for this class, but subclasses may want to
     * set this to other values.
     */
    public $_trailing_context_lines = 0;

    /**
     * Constructor.
     * @param array $params
     */
//HACK by domifara
//	function Text_Diff_Renderer($params = array())
    public function __construct($params = [])
    {
        foreach ($params as $param => $value) {
            $v = '_' . $param;
            if (isset($this->$v)) {
                $this->$v = $value;
            }
        }
    }

    /**
     * Get any renderer parameters.
     *
     * @return array  All parameters of this renderer object.
     */
    public function getParams()
    {
        $params = [];
        foreach (get_object_vars($this) as $k => $v) {
            if ('_' == $k{0}) {
                $params[substr($k, 1)] = $v;
            }
        }

        return $params;
    }

    /**
     * Renders a diff.
     *
     * @param Text_Diff $diff  A Text_Diff object.
     *
     * @return string  The formatted output.
     */
    public function render($diff)
    {
        $xi = $yi = 1;
        $block = false;
        $context = [];

        $nlead = $this->_leading_context_lines;
        $ntrail = $this->_trailing_context_lines;

        $output = $this->_startDiff();

        foreach ($diff->getDiff() as $edit) {
            if (is_a($edit, 'Text_Diff_Op_copy')) {
                if (is_array($block)) {
                    if (count($edit->orig) <= $nlead + $ntrail) {
                        $block[] = $edit;
                    } else {
                        if ($ntrail) {
                            $context = array_slice($edit->orig, 0, $ntrail);
                            $block[] = new Text_Diff_Op_copy($context);
                        }
                        $output .= $this->_block($x0, $ntrail + $xi - $x0,
                                                 $y0, $ntrail + $yi - $y0,
                                                 $block);
                        $block = false;
                    }
                }
                $context = $edit->orig;
            } else {
                if (!is_array($block)) {
                    $context = array_slice($context, count($context) - $nlead);
                    $x0 = $xi - count($context);
                    $y0 = $yi - count($context);
                    $block = [];
                    if ($context) {
                        $block[] = new Text_Diff_Op_copy($context);
                    }
                }
                $block[] = $edit;
            }

            if ($edit->orig) {
                $xi += count($edit->orig);
            }
            if ($edit->final) {
                $yi += count($edit->final);
            }
        }

        if (is_array($block)) {
            $output .= $this->_block($x0, $xi - $x0,
                                     $y0, $yi - $y0,
                                     $block);
        }

        return $output . $this->_endDiff();
    }

    public function _block($xbeg, $xlen, $ybeg, $ylen, $edits)
    {
        $output = $this->_startBlock($this->_blockHeader($xbeg, $xlen, $ybeg, $ylen));

        foreach ($edits as $edit) {
            switch (strtolower(get_class($edit))) {
            case 'text_diff_op_copy':
                $output .= $this->_context($edit->orig);
                break;

            case 'text_diff_op_add':
                $output .= $this->_added($edit->final);
                break;

            case 'text_diff_op_delete':
                $output .= $this->_deleted($edit->orig);
                break;

            case 'text_diff_op_change':
                $output .= $this->_changed($edit->orig, $edit->final);
                break;
            }
        }

        return $output . $this->_endBlock();
    }

    public function _startDiff()
    {
        return '';
    }

    public function _endDiff()
    {
        return '';
    }

    public function _blockHeader($xbeg, $xlen, $ybeg, $ylen)
    {
        if ($xlen > 1) {
            $xbeg .= ',' . ($xbeg + $xlen - 1);
        }
        if ($ylen > 1) {
            $ybeg .= ',' . ($ybeg + $ylen - 1);
        }

        return $xbeg . ($xlen ? ($ylen ? 'c' : 'd') : 'a') . $ybeg;
    }

    public function _startBlock($header)
    {
        return $header . "\n";
    }

    public function _endBlock()
    {
        return '';
    }

    public function _lines($lines, $prefix = ' ')
    {
        return $prefix . implode("\n$prefix", $lines) . "\n";
    }

    public function _context($lines)
    {
        return $this->_lines($lines);
    }

    public function _added($lines)
    {
        return $this->_lines($lines, '>');
    }

    public function _deleted($lines)
    {
        return $this->_lines($lines, '<');
    }

    public function _changed($orig, $final)
    {
        return $this->_deleted($orig) . "---\n" . $this->_added($final);
    }
}
