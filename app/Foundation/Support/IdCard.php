<?php

namespace App\Foundation\Support;

/**
 * 身份证
 * @author bzxx
 * @date 2023-01-28
 * @link http://www.ip33.com/shenfenzheng.html
 * @package App\Foundation\Support
 *
 * @desc
 *
 *  第一、二位表示省（自治区、直辖市、特别行政区）。
 *  第三、四位表示市（地级市、自治州、盟及国家直辖市所属市辖区和县的汇总码）。其中，01-20，51-70表示省直辖市；21-50表示地区（自治州、盟）。
 *  第五、六位表示县（市辖区、县级市、旗）。01-18表示市辖区或地区（自治州、盟）辖县级市；21-80表示县（旗）；81-99表示省直辖县级市。
 *  第七、十四位表示出生年月日（单数字月日左侧用0补齐）。其中年份用四位数字表示，年、月、日之间不用分隔符。例如：1981年05月11日就用19810511表示。
 *  第十五、十七位表示顺序码。对同地区、同年、月、日出生的人员编定的顺序号。其中第十七位奇数分给男性，偶数分给女性。
 *  第十八位表示校验码。作为尾号的校验码，是由号码编制单位按统一的公式计算出来的，校验码如果出现数字10，就用X来代替
 */
class IdCard
{
    public const PROVINCE = [
        11 => "北京",
        12 => "天津",
        13 => "河北",
        14 => "山西",
        15 => "内蒙古",
        21 => "辽宁",
        22 => "吉林",
        23 => "黑龙江",
        31 => "上海",
        32 => "江苏",
        33 => "浙江",
        34 => "安徽",
        35 => "福建",
        36 => "江西",
        37 => "山东",
        41 => "河南",
        42 => "湖北",
        43 => "湖南",
        44 => "广东",
        45 => "广西",
        46 => "海南",
        50 => "重庆",
        51 => "四川",
        52 => "贵州",
        53 => "云南",
        54 => "西藏",
        61 => "陕西",
        62 => "甘肃",
        63 => "青海",
        64 => "宁夏",
        65 => "新疆",
        71 => "台湾",
        81 => "香港",
        82 => "澳门",
        91 => "国外",
    ];

    /**
     * 获取身份证信息
     * @author bzxx
     * @date 2023-01-28
     * @param string $idCard
     * @param string|null $format 日期格式
     * @param string $man 男
     * @param string $woman 女
     * @return boolean[]|string[] => 成功失败,出生日期,性别,省份
     */
    public static function getInfo(string $idCard, string $format = null, string $man = '1', string $woman = '2'): array
    {
        $check = self::checkNumber($idCard);

        //验证失败 就返回空
        if (!$check) return [false, "", 0, 0];

        $birth = strlen($idCard) == 15 ? ('19' . substr($idCard, 6, 6)) : substr($idCard, 6, 8);

        $birthday = $format ? date($format, strtotime($birth)) : $birth; //获取出生年月

        // 15位身份证 最后一位是性别 18位是倒数第二位是性别
        $sex = substr($idCard, (strlen($idCard) == 15 ? -1 : -2), 1) % 2 ? $man : $woman; //1为男 2为女

        return [true, $birthday, $sex, (int)substr($idCard, 0, 2)];
    }

    /**
     * 身份证验证
     * @author bzxx
     * @date 2023-01-28
     * @param string $idCard
     * @return bool
     */
    public static function checkNumber(string $idCard): bool
    {
        // 身份证号 17位 或者 15位 (第一代居民身份证是15位编码)
        if (!preg_match('/^\d{17}(\d|x)$/i', $idCard) and !preg_match('/^\d{15}$/i', $idCard)) {
            return false;
        }

        $length = strlen($idCard);

        // 开头两位地区不在范围内
        if (!array_key_exists((int)substr($idCard, 0, 2), self::PROVINCE)) {
            return false;
        }

        // 如果第一代身份证需要补齐 年份 和 校验码
        $idCard = $length == 15 ? self::convertFirstType($idCard) : $idCard;

        $year = substr($idCard, 6, 4); //获取年份

        if ($year < 1900 || $year > 2078) return false;

        $birthday = substr($idCard, 6, 8); // 获取出生年月

        // 验证是否是正确的日期格式
        if ($birthday != date("Ymd", strtotime($birthday))) {
            return false;
        }

        //身份证编码规范验证
        return strtoupper(substr($idCard, -1)) == self::getVerifyCode(substr($idCard, 0, 17));
    }

    /**
     * 转化第一代身份证
     * @desc 需要补齐 年份 和 校验码
     * @author bzxx
     * @date 2023-01-28
     * @param string $idCard
     * @return string
     */
    private static function convertFirstType(string $idCard): string
    {
        $tmp = sprintf("%s19%s", substr($idCard, 0, 6), substr($idCard, 6));

        $bit18 = self::getVerifyCode($tmp); // 计算第18位校验码

        return sprintf("%s%s", $tmp, $bit18);
    }

    /**
     * 获取效检码
     * @author bzxx
     * @date 2023-01-28
     * @param string $string
     * @return false|string
     */
    public static function getVerifyCode(string $string)
    {
        if (strlen($string) != 17) {
            return false; // 长度不是17 返回false
        }

        //加权因子
        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];

        //校验码对应值
        $verify_number_list = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];

        $checksum = 0;

        for ($i = 0, $strlen = strlen($string); $i < $strlen; $i++) {
            $checksum += $string[$i] * $factor[$i];
        }

        $mod = $checksum % 11;

        return $verify_number_list[$mod];
    }
}
