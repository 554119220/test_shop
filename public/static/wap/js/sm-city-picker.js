/*!
 * =====================================================
 * SUI Mobile - http://m.sui.taobao.org/
 *
 * =====================================================
 */
// jshint ignore: start
+function($){

    $.smConfig.rawCitiesData = [
    {
        name: "北京",
        id: 1,
        upid: 0,
        sub: [
            {
                name: "市辖区",
                id: 2,
                upid: 1,
                sub: [
                    {
                        name: "东城区",
                        id: 3,
                        upid: 2
                    },
                    {
                        name: "西城区",
                        id: 4,
                        upid: 2
                    },
                    {
                        name: "崇文区",
                        id: 5,
                        upid: 2
                    },
                    {
                        name: "宣武区",
                        id: 6,
                        upid: 2
                    },
                    {
                        name: "朝阳区",
                        id: 7,
                        upid: 2
                    },
                    {
                        name: "丰台区",
                        id: 8,
                        upid: 2
                    },
                    {
                        name: "石景山区",
                        id: 9,
                        upid: 2
                    },
                    {
                        name: "海淀区",
                        id: 10,
                        upid: 2
                    },
                    {
                        name: "门头沟区",
                        id: 11,
                        upid: 2
                    },
                    {
                        name: "房山区",
                        id: 12,
                        upid: 2
                    },
                    {
                        name: "通州区",
                        id: 13,
                        upid: 2
                    },
                    {
                        name: "顺义区",
                        id: 14,
                        upid: 2
                    },
                    {
                        name: "昌平区",
                        id: 15,
                        upid: 2
                    },
                    {
                        name: "大兴区",
                        id: 16,
                        upid: 2
                    },
                    {
                        name: "怀柔区",
                        id: 17,
                        upid: 2
                    },
                    {
                        name: "平谷区",
                        id: 18,
                        upid: 2
                    }
                ]
            },
            {
                name: "县",
                id: 19,
                upid: 1,
                sub: [
                    {
                        name: "密云",
                        id: 20,
                        upid: 19
                    },
                    {
                        name: "延庆",
                        id: 21,
                        upid: 19
                    }
                ]
            }
        ]
    },
    {
        name: "天津",
        id: 22,
        upid: 0,
        sub: [
            {
                name: "市辖区",
                id: 23,
                upid: 22,
                sub: [
                    {
                        name: "和平区",
                        id: 24,
                        upid: 23
                    },
                    {
                        name: "河东区",
                        id: 25,
                        upid: 23
                    },
                    {
                        name: "河西区",
                        id: 26,
                        upid: 23
                    },
                    {
                        name: "南开区",
                        id: 27,
                        upid: 23
                    },
                    {
                        name: "河北区",
                        id: 28,
                        upid: 23
                    },
                    {
                        name: "红桥区",
                        id: 29,
                        upid: 23
                    },
                    {
                        name: "塘沽区",
                        id: 30,
                        upid: 23
                    },
                    {
                        name: "汉沽区",
                        id: 31,
                        upid: 23
                    },
                    {
                        name: "大港区",
                        id: 32,
                        upid: 23
                    },
                    {
                        name: "东丽区",
                        id: 33,
                        upid: 23
                    },
                    {
                        name: "西青区",
                        id: 34,
                        upid: 23
                    },
                    {
                        name: "津南区",
                        id: 35,
                        upid: 23
                    },
                    {
                        name: "北辰区",
                        id: 36,
                        upid: 23
                    },
                    {
                        name: "武清区",
                        id: 37,
                        upid: 23
                    },
                    {
                        name: "宝坻区",
                        id: 38,
                        upid: 23
                    }
                ]
            },
            {
                name: "县",
                id: 39,
                upid: 22,
                sub: [
                    {
                        name: "宁河",
                        id: 40,
                        upid: 39
                    },
                    {
                        name: "静海",
                        id: 41,
                        upid: 39
                    },
                    {
                        name: "蓟",
                        id: 42,
                        upid: 39
                    }
                ]
            }
        ]
    },
    {
        name: "河北",
        id: 43,
        upid: 0,
        sub: [
            {
                name: "石家庄市",
                id: 44,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 45,
                        upid: 44
                    },
                    {
                        name: "长安区",
                        id: 46,
                        upid: 44
                    },
                    {
                        name: "桥东区",
                        id: 47,
                        upid: 44
                    },
                    {
                        name: "桥西区",
                        id: 48,
                        upid: 44
                    },
                    {
                        name: "新华区",
                        id: 49,
                        upid: 44
                    },
                    {
                        name: "井陉矿区",
                        id: 50,
                        upid: 44
                    },
                    {
                        name: "裕华区",
                        id: 51,
                        upid: 44
                    },
                    {
                        name: "井陉县",
                        id: 52,
                        upid: 44
                    },
                    {
                        name: "正定县",
                        id: 53,
                        upid: 44
                    },
                    {
                        name: "栾城县",
                        id: 54,
                        upid: 44
                    },
                    {
                        name: "行唐县",
                        id: 55,
                        upid: 44
                    },
                    {
                        name: "灵寿县",
                        id: 56,
                        upid: 44
                    },
                    {
                        name: "高邑县",
                        id: 57,
                        upid: 44
                    },
                    {
                        name: "深泽县",
                        id: 58,
                        upid: 44
                    },
                    {
                        name: "赞皇县",
                        id: 59,
                        upid: 44
                    },
                    {
                        name: "无极县",
                        id: 60,
                        upid: 44
                    },
                    {
                        name: "平山县",
                        id: 61,
                        upid: 44
                    },
                    {
                        name: "元氏县",
                        id: 62,
                        upid: 44
                    },
                    {
                        name: "赵县",
                        id: 63,
                        upid: 44
                    },
                    {
                        name: "辛集市",
                        id: 64,
                        upid: 44
                    },
                    {
                        name: "藁城市",
                        id: 65,
                        upid: 44
                    },
                    {
                        name: "晋州市",
                        id: 66,
                        upid: 44
                    },
                    {
                        name: "新乐市",
                        id: 67,
                        upid: 44
                    },
                    {
                        name: "鹿泉市",
                        id: 68,
                        upid: 44
                    }
                ]
            },
            {
                name: "唐山市",
                id: 69,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 70,
                        upid: 69
                    },
                    {
                        name: "路南区",
                        id: 71,
                        upid: 69
                    },
                    {
                        name: "路北区",
                        id: 72,
                        upid: 69
                    },
                    {
                        name: "古冶区",
                        id: 73,
                        upid: 69
                    },
                    {
                        name: "开平区",
                        id: 74,
                        upid: 69
                    },
                    {
                        name: "丰南区",
                        id: 75,
                        upid: 69
                    },
                    {
                        name: "河北省丰润区",
                        id: 76,
                        upid: 69
                    },
                    {
                        name: "滦县",
                        id: 77,
                        upid: 69
                    },
                    {
                        name: "滦南县",
                        id: 78,
                        upid: 69
                    },
                    {
                        name: "乐亭县",
                        id: 79,
                        upid: 69
                    },
                    {
                        name: "迁西县",
                        id: 80,
                        upid: 69
                    },
                    {
                        name: "玉田县",
                        id: 81,
                        upid: 69
                    },
                    {
                        name: "唐海县",
                        id: 82,
                        upid: 69
                    },
                    {
                        name: "遵化市",
                        id: 83,
                        upid: 69
                    },
                    {
                        name: "迁安市",
                        id: 84,
                        upid: 69
                    }
                ]
            },
            {
                name: "秦皇岛市",
                id: 85,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 86,
                        upid: 85
                    },
                    {
                        name: "海港区",
                        id: 87,
                        upid: 85
                    },
                    {
                        name: "山海关区",
                        id: 88,
                        upid: 85
                    },
                    {
                        name: "北戴河区",
                        id: 89,
                        upid: 85
                    },
                    {
                        name: "青龙满族自治县",
                        id: 90,
                        upid: 85
                    },
                    {
                        name: "昌黎县",
                        id: 91,
                        upid: 85
                    },
                    {
                        name: "抚宁县",
                        id: 92,
                        upid: 85
                    },
                    {
                        name: "卢龙县",
                        id: 93,
                        upid: 85
                    }
                ]
            },
            {
                name: "邯郸市",
                id: 94,
                upid: 43,
                sub: [
                    {
                        name: "辖区",
                        id: 95,
                        upid: 94
                    },
                    {
                        name: "邯山区",
                        id: 96,
                        upid: 94
                    },
                    {
                        name: "丛台区",
                        id: 97,
                        upid: 94
                    },
                    {
                        name: "复兴区",
                        id: 98,
                        upid: 94
                    },
                    {
                        name: "峰峰矿区",
                        id: 99,
                        upid: 94
                    },
                    {
                        name: "邯郸县",
                        id: 100,
                        upid: 94
                    },
                    {
                        name: "临漳县",
                        id: 101,
                        upid: 94
                    },
                    {
                        name: "成安县",
                        id: 102,
                        upid: 94
                    },
                    {
                        name: "大名县",
                        id: 103,
                        upid: 94
                    },
                    {
                        name: "涉县",
                        id: 104,
                        upid: 94
                    },
                    {
                        name: "磁县",
                        id: 105,
                        upid: 94
                    },
                    {
                        name: "肥乡县",
                        id: 106,
                        upid: 94
                    },
                    {
                        name: "永年县",
                        id: 107,
                        upid: 94
                    },
                    {
                        name: "邱县",
                        id: 108,
                        upid: 94
                    },
                    {
                        name: "鸡泽县",
                        id: 109,
                        upid: 94
                    },
                    {
                        name: "广平县",
                        id: 110,
                        upid: 94
                    },
                    {
                        name: "馆陶县",
                        id: 111,
                        upid: 94
                    },
                    {
                        name: "魏县",
                        id: 112,
                        upid: 94
                    },
                    {
                        name: "曲周县",
                        id: 113,
                        upid: 94
                    },
                    {
                        name: "武安市",
                        id: 114,
                        upid: 94
                    }
                ]
            },
            {
                name: "邢台市",
                id: 115,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 116,
                        upid: 115
                    },
                    {
                        name: "桥东区",
                        id: 117,
                        upid: 115
                    },
                    {
                        name: "桥西区",
                        id: 118,
                        upid: 115
                    },
                    {
                        name: "邢台县",
                        id: 119,
                        upid: 115
                    },
                    {
                        name: "临城县",
                        id: 120,
                        upid: 115
                    },
                    {
                        name: "内丘县",
                        id: 121,
                        upid: 115
                    },
                    {
                        name: "柏乡县",
                        id: 122,
                        upid: 115
                    },
                    {
                        name: "隆尧县",
                        id: 123,
                        upid: 115
                    },
                    {
                        name: "任县",
                        id: 124,
                        upid: 115
                    },
                    {
                        name: "南和县",
                        id: 125,
                        upid: 115
                    },
                    {
                        name: "宁晋县",
                        id: 126,
                        upid: 115
                    },
                    {
                        name: "巨鹿县",
                        id: 127,
                        upid: 115
                    },
                    {
                        name: "新河县",
                        id: 128,
                        upid: 115
                    },
                    {
                        name: "广宗县",
                        id: 129,
                        upid: 115
                    },
                    {
                        name: "平乡县",
                        id: 130,
                        upid: 115
                    },
                    {
                        name: "威县",
                        id: 131,
                        upid: 115
                    },
                    {
                        name: "清河县",
                        id: 132,
                        upid: 115
                    },
                    {
                        name: "临西县",
                        id: 133,
                        upid: 115
                    },
                    {
                        name: "南宫市",
                        id: 134,
                        upid: 115
                    },
                    {
                        name: "沙河市",
                        id: 135,
                        upid: 115
                    }
                ]
            },
            {
                name: "保定市",
                id: 136,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 137,
                        upid: 136
                    },
                    {
                        name: "新市区",
                        id: 138,
                        upid: 136
                    },
                    {
                        name: "北市区",
                        id: 139,
                        upid: 136
                    },
                    {
                        name: "南市区",
                        id: 140,
                        upid: 136
                    },
                    {
                        name: "满城县",
                        id: 141,
                        upid: 136
                    },
                    {
                        name: "清苑县",
                        id: 142,
                        upid: 136
                    },
                    {
                        name: "涞水县",
                        id: 143,
                        upid: 136
                    },
                    {
                        name: "阜平县",
                        id: 144,
                        upid: 136
                    },
                    {
                        name: "徐水县",
                        id: 145,
                        upid: 136
                    },
                    {
                        name: "定兴县",
                        id: 146,
                        upid: 136
                    },
                    {
                        name: "唐县",
                        id: 147,
                        upid: 136
                    },
                    {
                        name: "高阳县",
                        id: 148,
                        upid: 136
                    },
                    {
                        name: "容城县",
                        id: 149,
                        upid: 136
                    },
                    {
                        name: "涞源县",
                        id: 150,
                        upid: 136
                    },
                    {
                        name: "望都县",
                        id: 151,
                        upid: 136
                    },
                    {
                        name: "安新县",
                        id: 152,
                        upid: 136
                    },
                    {
                        name: "易县",
                        id: 153,
                        upid: 136
                    },
                    {
                        name: "曲阳县",
                        id: 154,
                        upid: 136
                    },
                    {
                        name: "蠡县",
                        id: 155,
                        upid: 136
                    },
                    {
                        name: "顺平县",
                        id: 156,
                        upid: 136
                    },
                    {
                        name: "博野县",
                        id: 157,
                        upid: 136
                    },
                    {
                        name: "雄县",
                        id: 158,
                        upid: 136
                    },
                    {
                        name: "涿州市",
                        id: 159,
                        upid: 136
                    },
                    {
                        name: "定州市",
                        id: 160,
                        upid: 136
                    },
                    {
                        name: "安国市",
                        id: 161,
                        upid: 136
                    },
                    {
                        name: "高碑店市",
                        id: 162,
                        upid: 136
                    }
                ]
            },
            {
                name: "张家口市",
                id: 163,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 164,
                        upid: 163
                    },
                    {
                        name: "桥东区",
                        id: 165,
                        upid: 163
                    },
                    {
                        name: "桥西区",
                        id: 166,
                        upid: 163
                    },
                    {
                        name: "宣化区",
                        id: 167,
                        upid: 163
                    },
                    {
                        name: "下花园区",
                        id: 168,
                        upid: 163
                    },
                    {
                        name: "宣化县",
                        id: 169,
                        upid: 163
                    },
                    {
                        name: "张北县",
                        id: 170,
                        upid: 163
                    },
                    {
                        name: "康保县",
                        id: 171,
                        upid: 163
                    },
                    {
                        name: "沽源县",
                        id: 172,
                        upid: 163
                    },
                    {
                        name: "尚义县",
                        id: 173,
                        upid: 163
                    },
                    {
                        name: "蔚县",
                        id: 174,
                        upid: 163
                    },
                    {
                        name: "阳原县",
                        id: 175,
                        upid: 163
                    },
                    {
                        name: "怀安县",
                        id: 176,
                        upid: 163
                    },
                    {
                        name: "万全县",
                        id: 177,
                        upid: 163
                    },
                    {
                        name: "怀来县",
                        id: 178,
                        upid: 163
                    },
                    {
                        name: "涿鹿县",
                        id: 179,
                        upid: 163
                    },
                    {
                        name: "赤城县",
                        id: 180,
                        upid: 163
                    },
                    {
                        name: "崇礼县",
                        id: 181,
                        upid: 163
                    }
                ]
            },
            {
                name: "承德市",
                id: 182,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 183,
                        upid: 182
                    },
                    {
                        name: "双桥区",
                        id: 184,
                        upid: 182
                    },
                    {
                        name: "双滦区",
                        id: 185,
                        upid: 182
                    },
                    {
                        name: "鹰手营子矿区",
                        id: 186,
                        upid: 182
                    },
                    {
                        name: "承德县",
                        id: 187,
                        upid: 182
                    },
                    {
                        name: "兴隆县",
                        id: 188,
                        upid: 182
                    },
                    {
                        name: "平泉县",
                        id: 189,
                        upid: 182
                    },
                    {
                        name: "滦平县",
                        id: 190,
                        upid: 182
                    },
                    {
                        name: "隆化县",
                        id: 191,
                        upid: 182
                    },
                    {
                        name: "丰宁满族自治县",
                        id: 192,
                        upid: 182
                    },
                    {
                        name: "宽城满族自治县",
                        id: 193,
                        upid: 182
                    },
                    {
                        name: "围场满族蒙古族自治县",
                        id: 194,
                        upid: 182
                    }
                ]
            },
            {
                name: "沧州市",
                id: 195,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 196,
                        upid: 195
                    },
                    {
                        name: "新华区",
                        id: 197,
                        upid: 195
                    },
                    {
                        name: "运河区",
                        id: 198,
                        upid: 195
                    },
                    {
                        name: "沧县",
                        id: 199,
                        upid: 195
                    },
                    {
                        name: "青县",
                        id: 200,
                        upid: 195
                    },
                    {
                        name: "东光县",
                        id: 201,
                        upid: 195
                    },
                    {
                        name: "海兴县",
                        id: 202,
                        upid: 195
                    },
                    {
                        name: "盐山县",
                        id: 203,
                        upid: 195
                    },
                    {
                        name: "肃宁县",
                        id: 204,
                        upid: 195
                    },
                    {
                        name: "南皮县",
                        id: 205,
                        upid: 195
                    },
                    {
                        name: "吴桥县",
                        id: 206,
                        upid: 195
                    },
                    {
                        name: "献县",
                        id: 207,
                        upid: 195
                    },
                    {
                        name: "孟村回族自治县",
                        id: 208,
                        upid: 195
                    },
                    {
                        name: "泊头市",
                        id: 209,
                        upid: 195
                    },
                    {
                        name: "任丘市",
                        id: 210,
                        upid: 195
                    },
                    {
                        name: "黄骅市",
                        id: 211,
                        upid: 195
                    },
                    {
                        name: "河间市",
                        id: 212,
                        upid: 195
                    }
                ]
            },
            {
                name: "廊坊市",
                id: 213,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 214,
                        upid: 213
                    },
                    {
                        name: "安次区",
                        id: 215,
                        upid: 213
                    },
                    {
                        name: "广阳区",
                        id: 216,
                        upid: 213
                    },
                    {
                        name: "固安县",
                        id: 217,
                        upid: 213
                    },
                    {
                        name: "永清县",
                        id: 218,
                        upid: 213
                    },
                    {
                        name: "香河县",
                        id: 219,
                        upid: 213
                    },
                    {
                        name: "大城县",
                        id: 220,
                        upid: 213
                    },
                    {
                        name: "文安县",
                        id: 221,
                        upid: 213
                    },
                    {
                        name: "大厂回族自治县",
                        id: 222,
                        upid: 213
                    },
                    {
                        name: "霸州市",
                        id: 223,
                        upid: 213
                    },
                    {
                        name: "三河市",
                        id: 224,
                        upid: 213
                    }
                ]
            },
            {
                name: "衡水市",
                id: 225,
                upid: 43,
                sub: [
                    {
                        name: "市辖区",
                        id: 226,
                        upid: 225
                    },
                    {
                        name: "桃城区",
                        id: 227,
                        upid: 225
                    },
                    {
                        name: "枣强县",
                        id: 228,
                        upid: 225
                    },
                    {
                        name: "武邑县",
                        id: 229,
                        upid: 225
                    },
                    {
                        name: "武强县",
                        id: 230,
                        upid: 225
                    },
                    {
                        name: "饶阳县",
                        id: 231,
                        upid: 225
                    },
                    {
                        name: "安平县",
                        id: 232,
                        upid: 225
                    },
                    {
                        name: "故城县",
                        id: 233,
                        upid: 225
                    },
                    {
                        name: "景县",
                        id: 234,
                        upid: 225
                    },
                    {
                        name: "阜城县",
                        id: 235,
                        upid: 225
                    },
                    {
                        name: "冀州市",
                        id: 236,
                        upid: 225
                    },
                    {
                        name: "深州市",
                        id: 237,
                        upid: 225
                    }
                ]
            }
        ]
    },
    {
        name: "山西",
        id: 238,
        upid: 0,
        sub: [
            {
                name: "太原市",
                id: 239,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 240,
                        upid: 239
                    },
                    {
                        name: "小店区",
                        id: 241,
                        upid: 239
                    },
                    {
                        name: "迎泽区",
                        id: 242,
                        upid: 239
                    },
                    {
                        name: "杏花岭区",
                        id: 243,
                        upid: 239
                    },
                    {
                        name: "尖草坪区",
                        id: 244,
                        upid: 239
                    },
                    {
                        name: "万柏林区",
                        id: 245,
                        upid: 239
                    },
                    {
                        name: "晋源区",
                        id: 246,
                        upid: 239
                    },
                    {
                        name: "清徐县",
                        id: 247,
                        upid: 239
                    },
                    {
                        name: "阳曲县",
                        id: 248,
                        upid: 239
                    },
                    {
                        name: "娄烦县",
                        id: 249,
                        upid: 239
                    },
                    {
                        name: "古交市",
                        id: 250,
                        upid: 239
                    }
                ]
            },
            {
                name: "大同市",
                id: 251,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 252,
                        upid: 251
                    },
                    {
                        name: "城区",
                        id: 253,
                        upid: 251
                    },
                    {
                        name: "矿区",
                        id: 254,
                        upid: 251
                    },
                    {
                        name: "南郊区",
                        id: 255,
                        upid: 251
                    },
                    {
                        name: "新荣区",
                        id: 256,
                        upid: 251
                    },
                    {
                        name: "阳高县",
                        id: 257,
                        upid: 251
                    },
                    {
                        name: "天镇县",
                        id: 258,
                        upid: 251
                    },
                    {
                        name: "广灵县",
                        id: 259,
                        upid: 251
                    },
                    {
                        name: "灵丘县",
                        id: 260,
                        upid: 251
                    },
                    {
                        name: "浑源县",
                        id: 261,
                        upid: 251
                    },
                    {
                        name: "左云县",
                        id: 262,
                        upid: 251
                    },
                    {
                        name: "大同县",
                        id: 263,
                        upid: 251
                    }
                ]
            },
            {
                name: "阳泉市",
                id: 264,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 265,
                        upid: 264
                    },
                    {
                        name: "城区",
                        id: 266,
                        upid: 264
                    },
                    {
                        name: "矿区",
                        id: 267,
                        upid: 264
                    },
                    {
                        name: "郊区",
                        id: 268,
                        upid: 264
                    },
                    {
                        name: "平定县",
                        id: 269,
                        upid: 264
                    },
                    {
                        name: "盂县",
                        id: 270,
                        upid: 264
                    }
                ]
            },
            {
                name: "长治市",
                id: 271,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 272,
                        upid: 271
                    },
                    {
                        name: "城区",
                        id: 273,
                        upid: 271
                    },
                    {
                        name: "郊区",
                        id: 274,
                        upid: 271
                    },
                    {
                        name: "长治县",
                        id: 275,
                        upid: 271
                    },
                    {
                        name: "襄垣县",
                        id: 276,
                        upid: 271
                    },
                    {
                        name: "屯留县",
                        id: 277,
                        upid: 271
                    },
                    {
                        name: "平顺县",
                        id: 278,
                        upid: 271
                    },
                    {
                        name: "黎城县",
                        id: 279,
                        upid: 271
                    },
                    {
                        name: "壶关县",
                        id: 280,
                        upid: 271
                    },
                    {
                        name: "长子县",
                        id: 281,
                        upid: 271
                    },
                    {
                        name: "武乡县",
                        id: 282,
                        upid: 271
                    },
                    {
                        name: "沁县",
                        id: 283,
                        upid: 271
                    },
                    {
                        name: "沁源县",
                        id: 284,
                        upid: 271
                    },
                    {
                        name: "潞城市",
                        id: 285,
                        upid: 271
                    }
                ]
            },
            {
                name: "晋城市",
                id: 286,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 287,
                        upid: 286
                    },
                    {
                        name: "城区",
                        id: 288,
                        upid: 286
                    },
                    {
                        name: "沁水县",
                        id: 289,
                        upid: 286
                    },
                    {
                        name: "阳城县",
                        id: 290,
                        upid: 286
                    },
                    {
                        name: "陵川县",
                        id: 291,
                        upid: 286
                    },
                    {
                        name: "泽州县",
                        id: 292,
                        upid: 286
                    },
                    {
                        name: "高平市",
                        id: 293,
                        upid: 286
                    }
                ]
            },
            {
                name: "朔州市",
                id: 294,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 295,
                        upid: 294
                    },
                    {
                        name: "朔城区",
                        id: 296,
                        upid: 294
                    },
                    {
                        name: "平鲁区",
                        id: 297,
                        upid: 294
                    },
                    {
                        name: "山阴县",
                        id: 298,
                        upid: 294
                    },
                    {
                        name: "应县",
                        id: 299,
                        upid: 294
                    },
                    {
                        name: "右玉县",
                        id: 300,
                        upid: 294
                    },
                    {
                        name: "怀仁县",
                        id: 301,
                        upid: 294
                    }
                ]
            },
            {
                name: "晋中市",
                id: 302,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 303,
                        upid: 302
                    },
                    {
                        name: "榆次区",
                        id: 304,
                        upid: 302
                    },
                    {
                        name: "榆社县",
                        id: 305,
                        upid: 302
                    },
                    {
                        name: "左权县",
                        id: 306,
                        upid: 302
                    },
                    {
                        name: "和顺县",
                        id: 307,
                        upid: 302
                    },
                    {
                        name: "昔阳县",
                        id: 308,
                        upid: 302
                    },
                    {
                        name: "寿阳县",
                        id: 309,
                        upid: 302
                    },
                    {
                        name: "太谷县",
                        id: 310,
                        upid: 302
                    },
                    {
                        name: "祁县",
                        id: 311,
                        upid: 302
                    },
                    {
                        name: "平遥县",
                        id: 312,
                        upid: 302
                    },
                    {
                        name: "灵石县",
                        id: 313,
                        upid: 302
                    },
                    {
                        name: "介休市",
                        id: 314,
                        upid: 302
                    }
                ]
            },
            {
                name: "运城市",
                id: 315,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 316,
                        upid: 315
                    },
                    {
                        name: "盐湖区",
                        id: 317,
                        upid: 315
                    },
                    {
                        name: "临猗县",
                        id: 318,
                        upid: 315
                    },
                    {
                        name: "万荣县",
                        id: 319,
                        upid: 315
                    },
                    {
                        name: "闻喜县",
                        id: 320,
                        upid: 315
                    },
                    {
                        name: "稷山县",
                        id: 321,
                        upid: 315
                    },
                    {
                        name: "新绛县",
                        id: 322,
                        upid: 315
                    },
                    {
                        name: "绛县",
                        id: 323,
                        upid: 315
                    },
                    {
                        name: "垣曲县",
                        id: 324,
                        upid: 315
                    },
                    {
                        name: "夏县",
                        id: 325,
                        upid: 315
                    },
                    {
                        name: "平陆县",
                        id: 326,
                        upid: 315
                    },
                    {
                        name: "芮城县",
                        id: 327,
                        upid: 315
                    },
                    {
                        name: "永济市",
                        id: 328,
                        upid: 315
                    },
                    {
                        name: "河津市",
                        id: 329,
                        upid: 315
                    }
                ]
            },
            {
                name: "忻州市",
                id: 330,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 331,
                        upid: 330
                    },
                    {
                        name: "忻府区",
                        id: 332,
                        upid: 330
                    },
                    {
                        name: "定襄县",
                        id: 333,
                        upid: 330
                    },
                    {
                        name: "五台县",
                        id: 334,
                        upid: 330
                    },
                    {
                        name: "代县",
                        id: 335,
                        upid: 330
                    },
                    {
                        name: "繁峙县",
                        id: 336,
                        upid: 330
                    },
                    {
                        name: "宁武县",
                        id: 337,
                        upid: 330
                    },
                    {
                        name: "静乐县",
                        id: 338,
                        upid: 330
                    },
                    {
                        name: "神池县",
                        id: 339,
                        upid: 330
                    },
                    {
                        name: "五寨县",
                        id: 340,
                        upid: 330
                    },
                    {
                        name: "岢岚县",
                        id: 341,
                        upid: 330
                    },
                    {
                        name: "河曲县",
                        id: 342,
                        upid: 330
                    },
                    {
                        name: "保德县",
                        id: 343,
                        upid: 330
                    },
                    {
                        name: "偏关县",
                        id: 344,
                        upid: 330
                    },
                    {
                        name: "原平市",
                        id: 345,
                        upid: 330
                    }
                ]
            },
            {
                name: "临汾市",
                id: 346,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 347,
                        upid: 346
                    },
                    {
                        name: "尧都区",
                        id: 348,
                        upid: 346
                    },
                    {
                        name: "曲沃县",
                        id: 349,
                        upid: 346
                    },
                    {
                        name: "翼城县",
                        id: 350,
                        upid: 346
                    },
                    {
                        name: "襄汾县",
                        id: 351,
                        upid: 346
                    },
                    {
                        name: "洪洞县",
                        id: 352,
                        upid: 346
                    },
                    {
                        name: "古县",
                        id: 353,
                        upid: 346
                    },
                    {
                        name: "安泽县",
                        id: 354,
                        upid: 346
                    },
                    {
                        name: "浮山县",
                        id: 355,
                        upid: 346
                    },
                    {
                        name: "吉县",
                        id: 356,
                        upid: 346
                    },
                    {
                        name: "乡宁县",
                        id: 357,
                        upid: 346
                    },
                    {
                        name: "大宁县",
                        id: 358,
                        upid: 346
                    },
                    {
                        name: "隰县",
                        id: 359,
                        upid: 346
                    },
                    {
                        name: "永和县",
                        id: 360,
                        upid: 346
                    },
                    {
                        name: "蒲县",
                        id: 361,
                        upid: 346
                    },
                    {
                        name: "汾西县",
                        id: 362,
                        upid: 346
                    },
                    {
                        name: "侯马市",
                        id: 363,
                        upid: 346
                    },
                    {
                        name: "霍州市",
                        id: 364,
                        upid: 346
                    }
                ]
            },
            {
                name: "吕梁市",
                id: 365,
                upid: 238,
                sub: [
                    {
                        name: "市辖区",
                        id: 366,
                        upid: 365
                    },
                    {
                        name: "离石区",
                        id: 367,
                        upid: 365
                    },
                    {
                        name: "文水县",
                        id: 368,
                        upid: 365
                    },
                    {
                        name: "交城县",
                        id: 369,
                        upid: 365
                    },
                    {
                        name: "兴县",
                        id: 370,
                        upid: 365
                    },
                    {
                        name: "临县",
                        id: 371,
                        upid: 365
                    },
                    {
                        name: "柳林县",
                        id: 372,
                        upid: 365
                    },
                    {
                        name: "石楼县",
                        id: 373,
                        upid: 365
                    },
                    {
                        name: "岚县",
                        id: 374,
                        upid: 365
                    },
                    {
                        name: "方山县",
                        id: 375,
                        upid: 365
                    },
                    {
                        name: "中阳县",
                        id: 376,
                        upid: 365
                    },
                    {
                        name: "交口县",
                        id: 377,
                        upid: 365
                    },
                    {
                        name: "孝义市",
                        id: 378,
                        upid: 365
                    },
                    {
                        name: "汾阳市",
                        id: 379,
                        upid: 365
                    }
                ]
            }
        ]
    },
    {
        name: "内蒙古",
        id: 380,
        upid: 0,
        sub: [
            {
                name: "呼和浩特市",
                id: 381,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 382,
                        upid: 381
                    },
                    {
                        name: "新城区",
                        id: 383,
                        upid: 381
                    },
                    {
                        name: "回民区",
                        id: 384,
                        upid: 381
                    },
                    {
                        name: "玉泉区",
                        id: 385,
                        upid: 381
                    },
                    {
                        name: "赛罕区",
                        id: 386,
                        upid: 381
                    },
                    {
                        name: "土默特左旗",
                        id: 387,
                        upid: 381
                    },
                    {
                        name: "托克托县",
                        id: 388,
                        upid: 381
                    },
                    {
                        name: "和林格尔县",
                        id: 389,
                        upid: 381
                    },
                    {
                        name: "清水河县",
                        id: 390,
                        upid: 381
                    },
                    {
                        name: "武川县",
                        id: 391,
                        upid: 381
                    }
                ]
            },
            {
                name: "包头市",
                id: 392,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 393,
                        upid: 392
                    },
                    {
                        name: "东河区",
                        id: 394,
                        upid: 392
                    },
                    {
                        name: "昆都仑区",
                        id: 395,
                        upid: 392
                    },
                    {
                        name: "青山区",
                        id: 396,
                        upid: 392
                    },
                    {
                        name: "石拐区",
                        id: 397,
                        upid: 392
                    },
                    {
                        name: "白云矿区",
                        id: 398,
                        upid: 392
                    },
                    {
                        name: "九原区",
                        id: 399,
                        upid: 392
                    },
                    {
                        name: "土默特右旗",
                        id: 400,
                        upid: 392
                    },
                    {
                        name: "固阳县",
                        id: 401,
                        upid: 392
                    },
                    {
                        name: "达尔罕茂明安联合旗",
                        id: 402,
                        upid: 392
                    }
                ]
            },
            {
                name: "乌海市",
                id: 403,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 404,
                        upid: 403
                    },
                    {
                        name: "海勃湾区",
                        id: 405,
                        upid: 403
                    },
                    {
                        name: "海南区",
                        id: 406,
                        upid: 403
                    },
                    {
                        name: "乌达区",
                        id: 407,
                        upid: 403
                    }
                ]
            },
            {
                name: "赤峰市",
                id: 408,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 409,
                        upid: 408
                    },
                    {
                        name: "红山区",
                        id: 410,
                        upid: 408
                    },
                    {
                        name: "元宝山区",
                        id: 411,
                        upid: 408
                    },
                    {
                        name: "松山区",
                        id: 412,
                        upid: 408
                    },
                    {
                        name: "阿鲁科尔沁旗",
                        id: 413,
                        upid: 408
                    },
                    {
                        name: "巴林左旗",
                        id: 414,
                        upid: 408
                    },
                    {
                        name: "巴林右旗",
                        id: 415,
                        upid: 408
                    },
                    {
                        name: "林西县",
                        id: 416,
                        upid: 408
                    },
                    {
                        name: "克什克腾旗",
                        id: 417,
                        upid: 408
                    },
                    {
                        name: "翁牛特旗",
                        id: 418,
                        upid: 408
                    },
                    {
                        name: "喀喇沁旗",
                        id: 419,
                        upid: 408
                    },
                    {
                        name: "宁城县",
                        id: 420,
                        upid: 408
                    },
                    {
                        name: "敖汉旗",
                        id: 421,
                        upid: 408
                    }
                ]
            },
            {
                name: "通辽市",
                id: 422,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 423,
                        upid: 422
                    },
                    {
                        name: "科尔沁区",
                        id: 424,
                        upid: 422
                    },
                    {
                        name: "科尔沁左翼中旗",
                        id: 425,
                        upid: 422
                    },
                    {
                        name: "科尔沁左翼后旗",
                        id: 426,
                        upid: 422
                    },
                    {
                        name: "开鲁县",
                        id: 427,
                        upid: 422
                    },
                    {
                        name: "库伦旗",
                        id: 428,
                        upid: 422
                    },
                    {
                        name: "奈曼旗",
                        id: 429,
                        upid: 422
                    },
                    {
                        name: "扎鲁特旗",
                        id: 430,
                        upid: 422
                    },
                    {
                        name: "霍林郭勒市",
                        id: 431,
                        upid: 422
                    }
                ]
            },
            {
                name: "鄂尔多斯市",
                id: 432,
                upid: 380,
                sub: [
                    {
                        name: "东胜区",
                        id: 433,
                        upid: 432
                    },
                    {
                        name: "达拉特旗",
                        id: 434,
                        upid: 432
                    },
                    {
                        name: "准格尔旗",
                        id: 435,
                        upid: 432
                    },
                    {
                        name: "鄂托克前旗",
                        id: 436,
                        upid: 432
                    },
                    {
                        name: "鄂托克旗",
                        id: 437,
                        upid: 432
                    },
                    {
                        name: "杭锦旗",
                        id: 438,
                        upid: 432
                    },
                    {
                        name: "乌审旗",
                        id: 439,
                        upid: 432
                    },
                    {
                        name: "伊金霍洛旗",
                        id: 440,
                        upid: 432
                    }
                ]
            },
            {
                name: "呼伦贝尔市",
                id: 441,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 442,
                        upid: 441
                    },
                    {
                        name: "海拉尔区",
                        id: 443,
                        upid: 441
                    },
                    {
                        name: "阿荣旗",
                        id: 444,
                        upid: 441
                    },
                    {
                        name: "莫力达瓦达斡尔族自治旗",
                        id: 445,
                        upid: 441
                    },
                    {
                        name: "鄂伦春自治旗",
                        id: 446,
                        upid: 441
                    },
                    {
                        name: "鄂温克族自治旗",
                        id: 447,
                        upid: 441
                    },
                    {
                        name: "陈巴尔虎旗",
                        id: 448,
                        upid: 441
                    },
                    {
                        name: "新巴尔虎左旗",
                        id: 449,
                        upid: 441
                    },
                    {
                        name: "新巴尔虎右旗",
                        id: 450,
                        upid: 441
                    },
                    {
                        name: "满洲里市",
                        id: 451,
                        upid: 441
                    },
                    {
                        name: "牙克石市",
                        id: 452,
                        upid: 441
                    },
                    {
                        name: "扎兰屯市",
                        id: 453,
                        upid: 441
                    },
                    {
                        name: "额尔古纳市",
                        id: 454,
                        upid: 441
                    },
                    {
                        name: "根河市",
                        id: 455,
                        upid: 441
                    }
                ]
            },
            {
                name: "巴彦淖尔市",
                id: 456,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 457,
                        upid: 456
                    },
                    {
                        name: "临河区",
                        id: 458,
                        upid: 456
                    },
                    {
                        name: "五原县",
                        id: 459,
                        upid: 456
                    },
                    {
                        name: "磴口县",
                        id: 460,
                        upid: 456
                    },
                    {
                        name: "乌拉特前旗",
                        id: 461,
                        upid: 456
                    },
                    {
                        name: "乌拉特中旗",
                        id: 462,
                        upid: 456
                    },
                    {
                        name: "乌拉特后旗",
                        id: 463,
                        upid: 456
                    },
                    {
                        name: "杭锦后旗",
                        id: 464,
                        upid: 456
                    }
                ]
            },
            {
                name: "乌兰察布市",
                id: 465,
                upid: 380,
                sub: [
                    {
                        name: "市辖区",
                        id: 466,
                        upid: 465
                    },
                    {
                        name: "集宁区",
                        id: 467,
                        upid: 465
                    },
                    {
                        name: "卓资县",
                        id: 468,
                        upid: 465
                    },
                    {
                        name: "化德县",
                        id: 469,
                        upid: 465
                    },
                    {
                        name: "商都县",
                        id: 470,
                        upid: 465
                    },
                    {
                        name: "兴和县",
                        id: 471,
                        upid: 465
                    },
                    {
                        name: "凉城县",
                        id: 472,
                        upid: 465
                    },
                    {
                        name: "察哈尔右翼前旗",
                        id: 473,
                        upid: 465
                    },
                    {
                        name: "察哈尔右翼中旗",
                        id: 474,
                        upid: 465
                    },
                    {
                        name: "察哈尔右翼后旗",
                        id: 475,
                        upid: 465
                    },
                    {
                        name: "四子王旗",
                        id: 476,
                        upid: 465
                    },
                    {
                        name: "丰镇市",
                        id: 477,
                        upid: 465
                    }
                ]
            },
            {
                name: "兴安盟",
                id: 478,
                upid: 380,
                sub: [
                    {
                        name: "乌兰浩特市",
                        id: 479,
                        upid: 478
                    },
                    {
                        name: "阿尔山市",
                        id: 480,
                        upid: 478
                    },
                    {
                        name: "科尔沁右翼前旗",
                        id: 481,
                        upid: 478
                    },
                    {
                        name: "科尔沁右翼中旗",
                        id: 482,
                        upid: 478
                    },
                    {
                        name: "扎赉特旗",
                        id: 483,
                        upid: 478
                    },
                    {
                        name: "突泉县",
                        id: 484,
                        upid: 478
                    }
                ]
            },
            {
                name: "锡林郭勒盟",
                id: 485,
                upid: 380,
                sub: [
                    {
                        name: "二连浩特市",
                        id: 486,
                        upid: 485
                    },
                    {
                        name: "锡林浩特市",
                        id: 487,
                        upid: 485
                    },
                    {
                        name: "阿巴嘎旗",
                        id: 488,
                        upid: 485
                    },
                    {
                        name: "苏尼特左旗",
                        id: 489,
                        upid: 485
                    },
                    {
                        name: "苏尼特右旗",
                        id: 490,
                        upid: 485
                    },
                    {
                        name: "东乌珠穆沁旗",
                        id: 491,
                        upid: 485
                    },
                    {
                        name: "西乌珠穆沁旗",
                        id: 492,
                        upid: 485
                    },
                    {
                        name: "太仆寺旗",
                        id: 493,
                        upid: 485
                    },
                    {
                        name: "镶黄旗",
                        id: 494,
                        upid: 485
                    },
                    {
                        name: "正镶白旗",
                        id: 495,
                        upid: 485
                    },
                    {
                        name: "正蓝旗",
                        id: 496,
                        upid: 485
                    },
                    {
                        name: "多伦县",
                        id: 497,
                        upid: 485
                    }
                ]
            },
            {
                name: "乌兰察布盟",
                id: 498,
                upid: 380,
                sub: [ ]
            },
            {
                name: "阿拉善盟",
                id: 499,
                upid: 380,
                sub: [
                    {
                        name: "阿拉善左旗",
                        id: 500,
                        upid: 499
                    },
                    {
                        name: "阿拉善右旗",
                        id: 501,
                        upid: 499
                    },
                    {
                        name: "额济纳旗",
                        id: 502,
                        upid: 499
                    }
                ]
            }
        ]
    },
    {
        name: "辽宁",
        id: 503,
        upid: 0,
        sub: [
            {
                name: "沈阳市",
                id: 504,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 505,
                        upid: 504
                    },
                    {
                        name: "和平区",
                        id: 506,
                        upid: 504
                    },
                    {
                        name: "沈河区",
                        id: 507,
                        upid: 504
                    },
                    {
                        name: "大东区",
                        id: 508,
                        upid: 504
                    },
                    {
                        name: "皇姑区",
                        id: 509,
                        upid: 504
                    },
                    {
                        name: "铁西区",
                        id: 510,
                        upid: 504
                    },
                    {
                        name: "苏家屯区",
                        id: 511,
                        upid: 504
                    },
                    {
                        name: "东陵区",
                        id: 512,
                        upid: 504
                    },
                    {
                        name: "新城子区",
                        id: 513,
                        upid: 504
                    },
                    {
                        name: "于洪区",
                        id: 514,
                        upid: 504
                    },
                    {
                        name: "辽中县",
                        id: 515,
                        upid: 504
                    },
                    {
                        name: "康平县",
                        id: 516,
                        upid: 504
                    },
                    {
                        name: "法库县",
                        id: 517,
                        upid: 504
                    },
                    {
                        name: "新民市",
                        id: 518,
                        upid: 504
                    }
                ]
            },
            {
                name: "大连市",
                id: 519,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 520,
                        upid: 519
                    },
                    {
                        name: "中山区",
                        id: 521,
                        upid: 519
                    },
                    {
                        name: "西岗区",
                        id: 522,
                        upid: 519
                    },
                    {
                        name: "沙河口区",
                        id: 523,
                        upid: 519
                    },
                    {
                        name: "甘井子区",
                        id: 524,
                        upid: 519
                    },
                    {
                        name: "旅顺口区",
                        id: 525,
                        upid: 519
                    },
                    {
                        name: "金州区",
                        id: 526,
                        upid: 519
                    },
                    {
                        name: "长海县",
                        id: 527,
                        upid: 519
                    },
                    {
                        name: "瓦房店市",
                        id: 528,
                        upid: 519
                    },
                    {
                        name: "普兰店市",
                        id: 529,
                        upid: 519
                    },
                    {
                        name: "庄河市",
                        id: 530,
                        upid: 519
                    }
                ]
            },
            {
                name: "鞍山市",
                id: 531,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 532,
                        upid: 531
                    },
                    {
                        name: "铁东区",
                        id: 533,
                        upid: 531
                    },
                    {
                        name: "铁西区",
                        id: 534,
                        upid: 531
                    },
                    {
                        name: "立山区",
                        id: 535,
                        upid: 531
                    },
                    {
                        name: "千山区",
                        id: 536,
                        upid: 531
                    },
                    {
                        name: "台安县",
                        id: 537,
                        upid: 531
                    },
                    {
                        name: "岫岩满族自治县",
                        id: 538,
                        upid: 531
                    },
                    {
                        name: "海城市",
                        id: 539,
                        upid: 531
                    }
                ]
            },
            {
                name: "抚顺市",
                id: 540,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 541,
                        upid: 540
                    },
                    {
                        name: "新抚区",
                        id: 542,
                        upid: 540
                    },
                    {
                        name: "东洲区",
                        id: 543,
                        upid: 540
                    },
                    {
                        name: "望花区",
                        id: 544,
                        upid: 540
                    },
                    {
                        name: "顺城区",
                        id: 545,
                        upid: 540
                    },
                    {
                        name: "抚顺县",
                        id: 546,
                        upid: 540
                    },
                    {
                        name: "新宾满族自治县",
                        id: 547,
                        upid: 540
                    },
                    {
                        name: "清原满族自治县",
                        id: 548,
                        upid: 540
                    }
                ]
            },
            {
                name: "本溪市",
                id: 549,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 550,
                        upid: 549
                    },
                    {
                        name: "平山区",
                        id: 551,
                        upid: 549
                    },
                    {
                        name: "溪湖区",
                        id: 552,
                        upid: 549
                    },
                    {
                        name: "明山区",
                        id: 553,
                        upid: 549
                    },
                    {
                        name: "南芬区",
                        id: 554,
                        upid: 549
                    },
                    {
                        name: "本溪满族自治县",
                        id: 555,
                        upid: 549
                    },
                    {
                        name: "桓仁满族自治县",
                        id: 556,
                        upid: 549
                    }
                ]
            },
            {
                name: "丹东市",
                id: 557,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 558,
                        upid: 557
                    },
                    {
                        name: "元宝区",
                        id: 559,
                        upid: 557
                    },
                    {
                        name: "振兴区",
                        id: 560,
                        upid: 557
                    },
                    {
                        name: "振安区",
                        id: 561,
                        upid: 557
                    },
                    {
                        name: "宽甸满族自治县",
                        id: 562,
                        upid: 557
                    },
                    {
                        name: "东港市",
                        id: 563,
                        upid: 557
                    },
                    {
                        name: "凤城市",
                        id: 564,
                        upid: 557
                    }
                ]
            },
            {
                name: "锦州市",
                id: 565,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 566,
                        upid: 565
                    },
                    {
                        name: "古塔区",
                        id: 567,
                        upid: 565
                    },
                    {
                        name: "凌河区",
                        id: 568,
                        upid: 565
                    },
                    {
                        name: "太和区",
                        id: 569,
                        upid: 565
                    },
                    {
                        name: "黑山县",
                        id: 570,
                        upid: 565
                    },
                    {
                        name: "义县",
                        id: 571,
                        upid: 565
                    },
                    {
                        name: "凌海市",
                        id: 572,
                        upid: 565
                    },
                    {
                        name: "北宁市",
                        id: 573,
                        upid: 565
                    }
                ]
            },
            {
                name: "营口市",
                id: 574,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 575,
                        upid: 574
                    },
                    {
                        name: "站前区",
                        id: 576,
                        upid: 574
                    },
                    {
                        name: "西市区",
                        id: 577,
                        upid: 574
                    },
                    {
                        name: "鲅鱼圈区",
                        id: 578,
                        upid: 574
                    },
                    {
                        name: "老边区",
                        id: 579,
                        upid: 574
                    },
                    {
                        name: "盖州市",
                        id: 580,
                        upid: 574
                    },
                    {
                        name: "大石桥市",
                        id: 581,
                        upid: 574
                    }
                ]
            },
            {
                name: "阜新市",
                id: 582,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 583,
                        upid: 582
                    },
                    {
                        name: "海州区",
                        id: 584,
                        upid: 582
                    },
                    {
                        name: "新邱区",
                        id: 585,
                        upid: 582
                    },
                    {
                        name: "太平区",
                        id: 586,
                        upid: 582
                    },
                    {
                        name: "清河门区",
                        id: 587,
                        upid: 582
                    },
                    {
                        name: "细河区",
                        id: 588,
                        upid: 582
                    },
                    {
                        name: "阜新蒙古族自治县",
                        id: 589,
                        upid: 582
                    },
                    {
                        name: "彰武县",
                        id: 590,
                        upid: 582
                    }
                ]
            },
            {
                name: "辽阳市",
                id: 591,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 592,
                        upid: 591
                    },
                    {
                        name: "白塔区",
                        id: 593,
                        upid: 591
                    },
                    {
                        name: "文圣区",
                        id: 594,
                        upid: 591
                    },
                    {
                        name: "宏伟区",
                        id: 595,
                        upid: 591
                    },
                    {
                        name: "弓长岭区",
                        id: 596,
                        upid: 591
                    },
                    {
                        name: "太子河区",
                        id: 597,
                        upid: 591
                    },
                    {
                        name: "辽阳县",
                        id: 598,
                        upid: 591
                    },
                    {
                        name: "灯塔市",
                        id: 599,
                        upid: 591
                    }
                ]
            },
            {
                name: "盘锦市",
                id: 600,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 601,
                        upid: 600
                    },
                    {
                        name: "双台子区",
                        id: 602,
                        upid: 600
                    },
                    {
                        name: "兴隆台区",
                        id: 603,
                        upid: 600
                    },
                    {
                        name: "大洼县",
                        id: 604,
                        upid: 600
                    },
                    {
                        name: "盘山县",
                        id: 605,
                        upid: 600
                    }
                ]
            },
            {
                name: "铁岭市",
                id: 606,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 607,
                        upid: 606
                    },
                    {
                        name: "银州区",
                        id: 608,
                        upid: 606
                    },
                    {
                        name: "清河区",
                        id: 609,
                        upid: 606
                    },
                    {
                        name: "铁岭县",
                        id: 610,
                        upid: 606
                    },
                    {
                        name: "西丰县",
                        id: 611,
                        upid: 606
                    },
                    {
                        name: "昌图县",
                        id: 612,
                        upid: 606
                    },
                    {
                        name: "调兵山市",
                        id: 613,
                        upid: 606
                    },
                    {
                        name: "开原市",
                        id: 614,
                        upid: 606
                    }
                ]
            },
            {
                name: "朝阳市",
                id: 615,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 616,
                        upid: 615
                    },
                    {
                        name: "双塔区",
                        id: 617,
                        upid: 615
                    },
                    {
                        name: "龙城区",
                        id: 618,
                        upid: 615
                    },
                    {
                        name: "朝阳县",
                        id: 619,
                        upid: 615
                    },
                    {
                        name: "建平县",
                        id: 620,
                        upid: 615
                    },
                    {
                        name: "喀喇沁左翼蒙古族自治县",
                        id: 621,
                        upid: 615
                    },
                    {
                        name: "北票市",
                        id: 622,
                        upid: 615
                    },
                    {
                        name: "凌源市",
                        id: 623,
                        upid: 615
                    }
                ]
            },
            {
                name: "葫芦岛市",
                id: 624,
                upid: 503,
                sub: [
                    {
                        name: "市辖区",
                        id: 625,
                        upid: 624
                    },
                    {
                        name: "连山区",
                        id: 626,
                        upid: 624
                    },
                    {
                        name: "龙港区",
                        id: 627,
                        upid: 624
                    },
                    {
                        name: "南票区",
                        id: 628,
                        upid: 624
                    },
                    {
                        name: "绥中县",
                        id: 629,
                        upid: 624
                    },
                    {
                        name: "建昌县",
                        id: 630,
                        upid: 624
                    },
                    {
                        name: "兴城市",
                        id: 631,
                        upid: 624
                    }
                ]
            }
        ]
    },
    {
        name: "吉林",
        id: 632,
        upid: 0,
        sub: [
            {
                name: "长春市",
                id: 633,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 634,
                        upid: 633
                    },
                    {
                        name: "南关区",
                        id: 635,
                        upid: 633
                    },
                    {
                        name: "宽城区",
                        id: 636,
                        upid: 633
                    },
                    {
                        name: "朝阳区",
                        id: 637,
                        upid: 633
                    },
                    {
                        name: "二道区",
                        id: 638,
                        upid: 633
                    },
                    {
                        name: "绿园区",
                        id: 639,
                        upid: 633
                    },
                    {
                        name: "双阳区",
                        id: 640,
                        upid: 633
                    },
                    {
                        name: "农安县",
                        id: 641,
                        upid: 633
                    },
                    {
                        name: "九台市",
                        id: 642,
                        upid: 633
                    },
                    {
                        name: "榆树市",
                        id: 643,
                        upid: 633
                    },
                    {
                        name: "德惠市",
                        id: 644,
                        upid: 633
                    }
                ]
            },
            {
                name: "吉林市",
                id: 645,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 646,
                        upid: 645
                    },
                    {
                        name: "昌邑区",
                        id: 647,
                        upid: 645
                    },
                    {
                        name: "龙潭区",
                        id: 648,
                        upid: 645
                    },
                    {
                        name: "船营区",
                        id: 649,
                        upid: 645
                    },
                    {
                        name: "丰满区",
                        id: 650,
                        upid: 645
                    },
                    {
                        name: "永吉县",
                        id: 651,
                        upid: 645
                    },
                    {
                        name: "蛟河市",
                        id: 652,
                        upid: 645
                    },
                    {
                        name: "桦甸市",
                        id: 653,
                        upid: 645
                    },
                    {
                        name: "舒兰市",
                        id: 654,
                        upid: 645
                    },
                    {
                        name: "磐石市",
                        id: 655,
                        upid: 645
                    }
                ]
            },
            {
                name: "四平市",
                id: 656,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 657,
                        upid: 656
                    },
                    {
                        name: "铁西区",
                        id: 658,
                        upid: 656
                    },
                    {
                        name: "铁东区",
                        id: 659,
                        upid: 656
                    },
                    {
                        name: "梨树县",
                        id: 660,
                        upid: 656
                    },
                    {
                        name: "伊通满族自治县",
                        id: 661,
                        upid: 656
                    },
                    {
                        name: "公主岭市",
                        id: 662,
                        upid: 656
                    },
                    {
                        name: "双辽市",
                        id: 663,
                        upid: 656
                    }
                ]
            },
            {
                name: "辽源市",
                id: 664,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 665,
                        upid: 664
                    },
                    {
                        name: "龙山区",
                        id: 666,
                        upid: 664
                    },
                    {
                        name: "西安区",
                        id: 667,
                        upid: 664
                    },
                    {
                        name: "东丰县",
                        id: 668,
                        upid: 664
                    },
                    {
                        name: "东辽县",
                        id: 669,
                        upid: 664
                    }
                ]
            },
            {
                name: "通化市",
                id: 670,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 671,
                        upid: 670
                    },
                    {
                        name: "东昌区",
                        id: 672,
                        upid: 670
                    },
                    {
                        name: "二道江区",
                        id: 673,
                        upid: 670
                    },
                    {
                        name: "通化县",
                        id: 674,
                        upid: 670
                    },
                    {
                        name: "辉南县",
                        id: 675,
                        upid: 670
                    },
                    {
                        name: "柳河县",
                        id: 676,
                        upid: 670
                    },
                    {
                        name: "梅河口市",
                        id: 677,
                        upid: 670
                    },
                    {
                        name: "集安市",
                        id: 678,
                        upid: 670
                    }
                ]
            },
            {
                name: "白山市",
                id: 679,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 680,
                        upid: 679
                    },
                    {
                        name: "八道江区",
                        id: 681,
                        upid: 679
                    },
                    {
                        name: "抚松县",
                        id: 682,
                        upid: 679
                    },
                    {
                        name: "靖宇县",
                        id: 683,
                        upid: 679
                    },
                    {
                        name: "长白朝鲜族自治县",
                        id: 684,
                        upid: 679
                    },
                    {
                        name: "江源县",
                        id: 685,
                        upid: 679
                    },
                    {
                        name: "临江市",
                        id: 686,
                        upid: 679
                    }
                ]
            },
            {
                name: "松原市",
                id: 687,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 688,
                        upid: 687
                    },
                    {
                        name: "宁江区",
                        id: 689,
                        upid: 687
                    },
                    {
                        name: "前郭尔罗斯蒙古族自治县",
                        id: 690,
                        upid: 687
                    },
                    {
                        name: "长岭县",
                        id: 691,
                        upid: 687
                    },
                    {
                        name: "乾安县",
                        id: 692,
                        upid: 687
                    },
                    {
                        name: "扶余县",
                        id: 693,
                        upid: 687
                    }
                ]
            },
            {
                name: "白城市",
                id: 694,
                upid: 632,
                sub: [
                    {
                        name: "市辖区",
                        id: 695,
                        upid: 694
                    },
                    {
                        name: "洮北区",
                        id: 696,
                        upid: 694
                    },
                    {
                        name: "镇赉县",
                        id: 697,
                        upid: 694
                    },
                    {
                        name: "通榆县",
                        id: 698,
                        upid: 694
                    },
                    {
                        name: "洮南市",
                        id: 699,
                        upid: 694
                    },
                    {
                        name: "大安市",
                        id: 700,
                        upid: 694
                    }
                ]
            },
            {
                name: "延边朝鲜族自治州",
                id: 701,
                upid: 632,
                sub: [
                    {
                        name: "延吉市",
                        id: 702,
                        upid: 701
                    },
                    {
                        name: "图们市",
                        id: 703,
                        upid: 701
                    },
                    {
                        name: "敦化市",
                        id: 704,
                        upid: 701
                    },
                    {
                        name: "珲春市",
                        id: 705,
                        upid: 701
                    },
                    {
                        name: "龙井市",
                        id: 706,
                        upid: 701
                    },
                    {
                        name: "和龙市",
                        id: 707,
                        upid: 701
                    },
                    {
                        name: "汪清县",
                        id: 708,
                        upid: 701
                    },
                    {
                        name: "安图县",
                        id: 709,
                        upid: 701
                    }
                ]
            }
        ]
    },
    {
        name: "黑龙江",
        id: 710,
        upid: 0,
        sub: [
            {
                name: "哈尔滨市",
                id: 711,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 712,
                        upid: 711
                    },
                    {
                        name: "道里区",
                        id: 713,
                        upid: 711
                    },
                    {
                        name: "南岗区",
                        id: 714,
                        upid: 711
                    },
                    {
                        name: "道外区",
                        id: 715,
                        upid: 711
                    },
                    {
                        name: "香坊区",
                        id: 716,
                        upid: 711
                    },
                    {
                        name: "动力区",
                        id: 717,
                        upid: 711
                    },
                    {
                        name: "平房区",
                        id: 718,
                        upid: 711
                    },
                    {
                        name: "松北区",
                        id: 719,
                        upid: 711
                    },
                    {
                        name: "呼兰区",
                        id: 720,
                        upid: 711
                    },
                    {
                        name: "依兰县",
                        id: 721,
                        upid: 711
                    },
                    {
                        name: "方正县",
                        id: 722,
                        upid: 711
                    },
                    {
                        name: "宾县",
                        id: 723,
                        upid: 711
                    },
                    {
                        name: "巴彦县",
                        id: 724,
                        upid: 711
                    },
                    {
                        name: "木兰县",
                        id: 725,
                        upid: 711
                    },
                    {
                        name: "通河县",
                        id: 726,
                        upid: 711
                    },
                    {
                        name: "延寿县",
                        id: 727,
                        upid: 711
                    },
                    {
                        name: "阿城市",
                        id: 728,
                        upid: 711
                    },
                    {
                        name: "双城市",
                        id: 729,
                        upid: 711
                    },
                    {
                        name: "尚志市",
                        id: 730,
                        upid: 711
                    },
                    {
                        name: "五常市",
                        id: 731,
                        upid: 711
                    }
                ]
            },
            {
                name: "齐齐哈尔市",
                id: 732,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 733,
                        upid: 732
                    },
                    {
                        name: "龙沙区",
                        id: 734,
                        upid: 732
                    },
                    {
                        name: "建华区",
                        id: 735,
                        upid: 732
                    },
                    {
                        name: "铁锋区",
                        id: 736,
                        upid: 732
                    },
                    {
                        name: "昂昂溪区",
                        id: 737,
                        upid: 732
                    },
                    {
                        name: "富拉尔基区",
                        id: 738,
                        upid: 732
                    },
                    {
                        name: "碾子山区",
                        id: 739,
                        upid: 732
                    },
                    {
                        name: "梅里斯达斡尔",
                        id: 740,
                        upid: 732
                    },
                    {
                        name: "龙江县",
                        id: 741,
                        upid: 732
                    },
                    {
                        name: "依安县",
                        id: 742,
                        upid: 732
                    },
                    {
                        name: "泰来县",
                        id: 743,
                        upid: 732
                    },
                    {
                        name: "甘南县",
                        id: 744,
                        upid: 732
                    },
                    {
                        name: "富裕县",
                        id: 745,
                        upid: 732
                    },
                    {
                        name: "克山县",
                        id: 746,
                        upid: 732
                    },
                    {
                        name: "克东县",
                        id: 747,
                        upid: 732
                    },
                    {
                        name: "拜泉县",
                        id: 748,
                        upid: 732
                    },
                    {
                        name: "讷河市",
                        id: 749,
                        upid: 732
                    }
                ]
            },
            {
                name: "鸡西市",
                id: 750,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 751,
                        upid: 750
                    },
                    {
                        name: "鸡冠区",
                        id: 752,
                        upid: 750
                    },
                    {
                        name: "恒山区",
                        id: 753,
                        upid: 750
                    },
                    {
                        name: "滴道区",
                        id: 754,
                        upid: 750
                    },
                    {
                        name: "梨树区",
                        id: 755,
                        upid: 750
                    },
                    {
                        name: "城子河区",
                        id: 756,
                        upid: 750
                    },
                    {
                        name: "麻山区",
                        id: 757,
                        upid: 750
                    },
                    {
                        name: "鸡东县",
                        id: 758,
                        upid: 750
                    },
                    {
                        name: "虎林市",
                        id: 759,
                        upid: 750
                    },
                    {
                        name: "密山市",
                        id: 760,
                        upid: 750
                    }
                ]
            },
            {
                name: "鹤岗市",
                id: 761,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 762,
                        upid: 761
                    },
                    {
                        name: "向阳区",
                        id: 763,
                        upid: 761
                    },
                    {
                        name: "工农区",
                        id: 764,
                        upid: 761
                    },
                    {
                        name: "南山区",
                        id: 765,
                        upid: 761
                    },
                    {
                        name: "兴安区",
                        id: 766,
                        upid: 761
                    },
                    {
                        name: "东山区",
                        id: 767,
                        upid: 761
                    },
                    {
                        name: "兴山区",
                        id: 768,
                        upid: 761
                    },
                    {
                        name: "萝北县",
                        id: 769,
                        upid: 761
                    },
                    {
                        name: "绥滨县",
                        id: 770,
                        upid: 761
                    }
                ]
            },
            {
                name: "双鸭山市",
                id: 771,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 772,
                        upid: 771
                    },
                    {
                        name: "尖山区",
                        id: 773,
                        upid: 771
                    },
                    {
                        name: "岭东区",
                        id: 774,
                        upid: 771
                    },
                    {
                        name: "四方台区",
                        id: 775,
                        upid: 771
                    },
                    {
                        name: "宝山区",
                        id: 776,
                        upid: 771
                    },
                    {
                        name: "集贤县",
                        id: 777,
                        upid: 771
                    },
                    {
                        name: "友谊县",
                        id: 778,
                        upid: 771
                    },
                    {
                        name: "宝清县",
                        id: 779,
                        upid: 771
                    },
                    {
                        name: "饶河县",
                        id: 780,
                        upid: 771
                    }
                ]
            },
            {
                name: "大庆市",
                id: 781,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 782,
                        upid: 781
                    },
                    {
                        name: "萨尔图区",
                        id: 783,
                        upid: 781
                    },
                    {
                        name: "龙凤区",
                        id: 784,
                        upid: 781
                    },
                    {
                        name: "让胡路区",
                        id: 785,
                        upid: 781
                    },
                    {
                        name: "红岗区",
                        id: 786,
                        upid: 781
                    },
                    {
                        name: "大同区",
                        id: 787,
                        upid: 781
                    },
                    {
                        name: "肇州县",
                        id: 788,
                        upid: 781
                    },
                    {
                        name: "肇源县",
                        id: 789,
                        upid: 781
                    },
                    {
                        name: "林甸县",
                        id: 790,
                        upid: 781
                    },
                    {
                        name: "杜尔伯特蒙古族自治县",
                        id: 791,
                        upid: 781
                    }
                ]
            },
            {
                name: "伊春市",
                id: 792,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 793,
                        upid: 792
                    },
                    {
                        name: "伊春区",
                        id: 794,
                        upid: 792
                    },
                    {
                        name: "南岔区",
                        id: 795,
                        upid: 792
                    },
                    {
                        name: "友好区",
                        id: 796,
                        upid: 792
                    },
                    {
                        name: "西林区",
                        id: 797,
                        upid: 792
                    },
                    {
                        name: "翠峦区",
                        id: 798,
                        upid: 792
                    },
                    {
                        name: "新青区",
                        id: 799,
                        upid: 792
                    },
                    {
                        name: "美溪区",
                        id: 800,
                        upid: 792
                    },
                    {
                        name: "金山屯区",
                        id: 801,
                        upid: 792
                    },
                    {
                        name: "五营区",
                        id: 802,
                        upid: 792
                    },
                    {
                        name: "乌马河区",
                        id: 803,
                        upid: 792
                    },
                    {
                        name: "汤旺河区",
                        id: 804,
                        upid: 792
                    },
                    {
                        name: "带岭区",
                        id: 805,
                        upid: 792
                    },
                    {
                        name: "乌伊岭区",
                        id: 806,
                        upid: 792
                    },
                    {
                        name: "红星区",
                        id: 807,
                        upid: 792
                    },
                    {
                        name: "上甘岭区",
                        id: 808,
                        upid: 792
                    },
                    {
                        name: "嘉荫县",
                        id: 809,
                        upid: 792
                    },
                    {
                        name: "铁力市",
                        id: 810,
                        upid: 792
                    }
                ]
            },
            {
                name: "佳木斯市",
                id: 811,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 812,
                        upid: 811
                    },
                    {
                        name: "永红区",
                        id: 813,
                        upid: 811
                    },
                    {
                        name: "向阳区",
                        id: 814,
                        upid: 811
                    },
                    {
                        name: "前进区",
                        id: 815,
                        upid: 811
                    },
                    {
                        name: "东风区",
                        id: 816,
                        upid: 811
                    },
                    {
                        name: "郊区",
                        id: 817,
                        upid: 811
                    },
                    {
                        name: "桦南县",
                        id: 818,
                        upid: 811
                    },
                    {
                        name: "桦川县",
                        id: 819,
                        upid: 811
                    },
                    {
                        name: "汤原县",
                        id: 820,
                        upid: 811
                    },
                    {
                        name: "抚远县",
                        id: 821,
                        upid: 811
                    },
                    {
                        name: "同江市",
                        id: 822,
                        upid: 811
                    },
                    {
                        name: "富锦市",
                        id: 823,
                        upid: 811
                    }
                ]
            },
            {
                name: "七台河市",
                id: 824,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 825,
                        upid: 824
                    },
                    {
                        name: "新兴区",
                        id: 826,
                        upid: 824
                    },
                    {
                        name: "桃山区",
                        id: 827,
                        upid: 824
                    },
                    {
                        name: "茄子河区",
                        id: 828,
                        upid: 824
                    },
                    {
                        name: "勃利县",
                        id: 829,
                        upid: 824
                    }
                ]
            },
            {
                name: "牡丹江市",
                id: 830,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 831,
                        upid: 830
                    },
                    {
                        name: "东安区",
                        id: 832,
                        upid: 830
                    },
                    {
                        name: "阳明区",
                        id: 833,
                        upid: 830
                    },
                    {
                        name: "爱民区",
                        id: 834,
                        upid: 830
                    },
                    {
                        name: "西安区",
                        id: 835,
                        upid: 830
                    },
                    {
                        name: "东宁县",
                        id: 836,
                        upid: 830
                    },
                    {
                        name: "林口县",
                        id: 837,
                        upid: 830
                    },
                    {
                        name: "绥芬河市",
                        id: 838,
                        upid: 830
                    },
                    {
                        name: "海林市",
                        id: 839,
                        upid: 830
                    },
                    {
                        name: "宁安市",
                        id: 840,
                        upid: 830
                    },
                    {
                        name: "穆棱市",
                        id: 841,
                        upid: 830
                    }
                ]
            },
            {
                name: "黑河市",
                id: 842,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 843,
                        upid: 842
                    },
                    {
                        name: "爱辉区",
                        id: 844,
                        upid: 842
                    },
                    {
                        name: "嫩江县",
                        id: 845,
                        upid: 842
                    },
                    {
                        name: "逊克县",
                        id: 846,
                        upid: 842
                    },
                    {
                        name: "孙吴县",
                        id: 847,
                        upid: 842
                    },
                    {
                        name: "北安市",
                        id: 848,
                        upid: 842
                    },
                    {
                        name: "五大连池市",
                        id: 849,
                        upid: 842
                    }
                ]
            },
            {
                name: "绥化市",
                id: 850,
                upid: 710,
                sub: [
                    {
                        name: "市辖区",
                        id: 851,
                        upid: 850
                    },
                    {
                        name: "北林区",
                        id: 852,
                        upid: 850
                    },
                    {
                        name: "望奎县",
                        id: 853,
                        upid: 850
                    },
                    {
                        name: "兰西县",
                        id: 854,
                        upid: 850
                    },
                    {
                        name: "青冈县",
                        id: 855,
                        upid: 850
                    },
                    {
                        name: "庆安县",
                        id: 856,
                        upid: 850
                    },
                    {
                        name: "明水县",
                        id: 857,
                        upid: 850
                    },
                    {
                        name: "绥棱县",
                        id: 858,
                        upid: 850
                    },
                    {
                        name: "安达市",
                        id: 859,
                        upid: 850
                    },
                    {
                        name: "肇东市",
                        id: 860,
                        upid: 850
                    },
                    {
                        name: "海伦市",
                        id: 861,
                        upid: 850
                    }
                ]
            },
            {
                name: "大兴安岭地区",
                id: 862,
                upid: 710,
                sub: [
                    {
                        name: "呼玛县",
                        id: 863,
                        upid: 862
                    },
                    {
                        name: "塔河县",
                        id: 864,
                        upid: 862
                    },
                    {
                        name: "漠河县",
                        id: 865,
                        upid: 862
                    }
                ]
            }
        ]
    },
    {
        name: "上海",
        id: 866,
        upid: 0,
        sub: [
            {
                name: "市辖区",
                id: 867,
                upid: 866,
                sub: [
                    {
                        name: "黄浦区",
                        id: 868,
                        upid: 867
                    },
                    {
                        name: "卢湾区",
                        id: 869,
                        upid: 867
                    },
                    {
                        name: "徐汇区",
                        id: 870,
                        upid: 867
                    },
                    {
                        name: "长宁区",
                        id: 871,
                        upid: 867
                    },
                    {
                        name: "静安区",
                        id: 872,
                        upid: 867
                    },
                    {
                        name: "普陀区",
                        id: 873,
                        upid: 867
                    },
                    {
                        name: "闸北区",
                        id: 874,
                        upid: 867
                    },
                    {
                        name: "虹口区",
                        id: 875,
                        upid: 867
                    },
                    {
                        name: "杨浦区",
                        id: 876,
                        upid: 867
                    },
                    {
                        name: "闵行区",
                        id: 877,
                        upid: 867
                    },
                    {
                        name: "宝山区",
                        id: 878,
                        upid: 867
                    },
                    {
                        name: "嘉定区",
                        id: 879,
                        upid: 867
                    },
                    {
                        name: "浦东新区",
                        id: 880,
                        upid: 867
                    },
                    {
                        name: "金山区",
                        id: 881,
                        upid: 867
                    },
                    {
                        name: "松江区",
                        id: 882,
                        upid: 867
                    },
                    {
                        name: "青浦区",
                        id: 883,
                        upid: 867
                    },
                    {
                        name: "南汇区",
                        id: 884,
                        upid: 867
                    },
                    {
                        name: "奉贤区",
                        id: 885,
                        upid: 867
                    }
                ]
            },
            {
                name: "县",
                id: 886,
                upid: 866,
                sub: [
                    {
                        name: "崇明",
                        id: 887,
                        upid: 886
                    }
                ]
            }
        ]
    },
    {
        name: "江苏",
        id: 888,
        upid: 0,
        sub: [
            {
                name: "南京市",
                id: 889,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 890,
                        upid: 889
                    },
                    {
                        name: "玄武区",
                        id: 891,
                        upid: 889
                    },
                    {
                        name: "白下区",
                        id: 892,
                        upid: 889
                    },
                    {
                        name: "秦淮区",
                        id: 893,
                        upid: 889
                    },
                    {
                        name: "建邺区",
                        id: 894,
                        upid: 889
                    },
                    {
                        name: "鼓楼区",
                        id: 895,
                        upid: 889
                    },
                    {
                        name: "下关区",
                        id: 896,
                        upid: 889
                    },
                    {
                        name: "浦口区",
                        id: 897,
                        upid: 889
                    },
                    {
                        name: "栖霞区",
                        id: 898,
                        upid: 889
                    },
                    {
                        name: "雨花台区",
                        id: 899,
                        upid: 889
                    },
                    {
                        name: "江宁区",
                        id: 900,
                        upid: 889
                    },
                    {
                        name: "六合区",
                        id: 901,
                        upid: 889
                    },
                    {
                        name: "溧水县",
                        id: 902,
                        upid: 889
                    },
                    {
                        name: "高淳县",
                        id: 903,
                        upid: 889
                    }
                ]
            },
            {
                name: "无锡市",
                id: 904,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 905,
                        upid: 904
                    },
                    {
                        name: "崇安区",
                        id: 906,
                        upid: 904
                    },
                    {
                        name: "南长区",
                        id: 907,
                        upid: 904
                    },
                    {
                        name: "北塘区",
                        id: 908,
                        upid: 904
                    },
                    {
                        name: "锡山区",
                        id: 909,
                        upid: 904
                    },
                    {
                        name: "惠山区",
                        id: 910,
                        upid: 904
                    },
                    {
                        name: "滨湖区",
                        id: 911,
                        upid: 904
                    },
                    {
                        name: "江阴市",
                        id: 912,
                        upid: 904
                    },
                    {
                        name: "宜兴市",
                        id: 913,
                        upid: 904
                    }
                ]
            },
            {
                name: "徐州市",
                id: 914,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 915,
                        upid: 914
                    },
                    {
                        name: "鼓楼区",
                        id: 916,
                        upid: 914
                    },
                    {
                        name: "云龙区",
                        id: 917,
                        upid: 914
                    },
                    {
                        name: "九里区",
                        id: 918,
                        upid: 914
                    },
                    {
                        name: "贾汪区",
                        id: 919,
                        upid: 914
                    },
                    {
                        name: "泉山区",
                        id: 920,
                        upid: 914
                    },
                    {
                        name: "丰县",
                        id: 921,
                        upid: 914
                    },
                    {
                        name: "沛县",
                        id: 922,
                        upid: 914
                    },
                    {
                        name: "铜山县",
                        id: 923,
                        upid: 914
                    },
                    {
                        name: "睢宁县",
                        id: 924,
                        upid: 914
                    },
                    {
                        name: "新沂市",
                        id: 925,
                        upid: 914
                    },
                    {
                        name: "邳州市",
                        id: 926,
                        upid: 914
                    }
                ]
            },
            {
                name: "常州市",
                id: 927,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 928,
                        upid: 927
                    },
                    {
                        name: "天宁区",
                        id: 929,
                        upid: 927
                    },
                    {
                        name: "钟楼区",
                        id: 930,
                        upid: 927
                    },
                    {
                        name: "戚墅堰区",
                        id: 931,
                        upid: 927
                    },
                    {
                        name: "新北区",
                        id: 932,
                        upid: 927
                    },
                    {
                        name: "武进区",
                        id: 933,
                        upid: 927
                    },
                    {
                        name: "溧阳市",
                        id: 934,
                        upid: 927
                    },
                    {
                        name: "金坛市",
                        id: 935,
                        upid: 927
                    }
                ]
            },
            {
                name: "苏州市",
                id: 936,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 937,
                        upid: 936
                    },
                    {
                        name: "沧浪区",
                        id: 938,
                        upid: 936
                    },
                    {
                        name: "平江区",
                        id: 939,
                        upid: 936
                    },
                    {
                        name: "金阊区",
                        id: 940,
                        upid: 936
                    },
                    {
                        name: "虎丘区",
                        id: 941,
                        upid: 936
                    },
                    {
                        name: "吴中区",
                        id: 942,
                        upid: 936
                    },
                    {
                        name: "相城区",
                        id: 943,
                        upid: 936
                    },
                    {
                        name: "常熟市",
                        id: 944,
                        upid: 936
                    },
                    {
                        name: "张家港市",
                        id: 945,
                        upid: 936
                    },
                    {
                        name: "昆山市",
                        id: 946,
                        upid: 936
                    },
                    {
                        name: "吴江市",
                        id: 947,
                        upid: 936
                    },
                    {
                        name: "太仓市",
                        id: 948,
                        upid: 936
                    }
                ]
            },
            {
                name: "南通市",
                id: 949,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 950,
                        upid: 949
                    },
                    {
                        name: "崇川区",
                        id: 951,
                        upid: 949
                    },
                    {
                        name: "港闸区",
                        id: 952,
                        upid: 949
                    },
                    {
                        name: "海安县",
                        id: 953,
                        upid: 949
                    },
                    {
                        name: "如东县",
                        id: 954,
                        upid: 949
                    },
                    {
                        name: "启东市",
                        id: 955,
                        upid: 949
                    },
                    {
                        name: "如皋市",
                        id: 956,
                        upid: 949
                    },
                    {
                        name: "通州市",
                        id: 957,
                        upid: 949
                    },
                    {
                        name: "海门市",
                        id: 958,
                        upid: 949
                    }
                ]
            },
            {
                name: "连云港市",
                id: 959,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 960,
                        upid: 959
                    },
                    {
                        name: "连云区",
                        id: 961,
                        upid: 959
                    },
                    {
                        name: "新浦区",
                        id: 962,
                        upid: 959
                    },
                    {
                        name: "海州区",
                        id: 963,
                        upid: 959
                    },
                    {
                        name: "赣榆县",
                        id: 964,
                        upid: 959
                    },
                    {
                        name: "东海县",
                        id: 965,
                        upid: 959
                    },
                    {
                        name: "灌云县",
                        id: 966,
                        upid: 959
                    },
                    {
                        name: "灌南县",
                        id: 967,
                        upid: 959
                    }
                ]
            },
            {
                name: "淮安市",
                id: 968,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 969,
                        upid: 968
                    },
                    {
                        name: "清河区",
                        id: 970,
                        upid: 968
                    },
                    {
                        name: "楚州区",
                        id: 971,
                        upid: 968
                    },
                    {
                        name: "淮阴区",
                        id: 972,
                        upid: 968
                    },
                    {
                        name: "清浦区",
                        id: 973,
                        upid: 968
                    },
                    {
                        name: "涟水县",
                        id: 974,
                        upid: 968
                    },
                    {
                        name: "洪泽县",
                        id: 975,
                        upid: 968
                    },
                    {
                        name: "盱眙县",
                        id: 976,
                        upid: 968
                    },
                    {
                        name: "金湖县",
                        id: 977,
                        upid: 968
                    }
                ]
            },
            {
                name: "盐城市",
                id: 978,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 979,
                        upid: 978
                    },
                    {
                        name: "亭湖区",
                        id: 980,
                        upid: 978
                    },
                    {
                        name: "盐都区",
                        id: 981,
                        upid: 978
                    },
                    {
                        name: "响水县",
                        id: 982,
                        upid: 978
                    },
                    {
                        name: "滨海县",
                        id: 983,
                        upid: 978
                    },
                    {
                        name: "阜宁县",
                        id: 984,
                        upid: 978
                    },
                    {
                        name: "射阳县",
                        id: 985,
                        upid: 978
                    },
                    {
                        name: "建湖县",
                        id: 986,
                        upid: 978
                    },
                    {
                        name: "东台市",
                        id: 987,
                        upid: 978
                    },
                    {
                        name: "大丰市",
                        id: 988,
                        upid: 978
                    }
                ]
            },
            {
                name: "扬州市",
                id: 989,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 990,
                        upid: 989
                    },
                    {
                        name: "广陵区",
                        id: 991,
                        upid: 989
                    },
                    {
                        name: "邗江区",
                        id: 992,
                        upid: 989
                    },
                    {
                        name: "郊区",
                        id: 993,
                        upid: 989
                    },
                    {
                        name: "宝应县",
                        id: 994,
                        upid: 989
                    },
                    {
                        name: "仪征市",
                        id: 995,
                        upid: 989
                    },
                    {
                        name: "高邮市",
                        id: 996,
                        upid: 989
                    },
                    {
                        name: "江都市",
                        id: 997,
                        upid: 989
                    }
                ]
            },
            {
                name: "镇江市",
                id: 998,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 999,
                        upid: 998
                    },
                    {
                        name: "京口区",
                        id: 1000,
                        upid: 998
                    },
                    {
                        name: "润州区",
                        id: 1001,
                        upid: 998
                    },
                    {
                        name: "丹徒区",
                        id: 1002,
                        upid: 998
                    },
                    {
                        name: "丹阳市",
                        id: 1003,
                        upid: 998
                    },
                    {
                        name: "扬中市",
                        id: 1004,
                        upid: 998
                    },
                    {
                        name: "句容市",
                        id: 1005,
                        upid: 998
                    }
                ]
            },
            {
                name: "泰州市",
                id: 1006,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 1007,
                        upid: 1006
                    },
                    {
                        name: "海陵区",
                        id: 1008,
                        upid: 1006
                    },
                    {
                        name: "高港区",
                        id: 1009,
                        upid: 1006
                    },
                    {
                        name: "兴化市",
                        id: 1010,
                        upid: 1006
                    },
                    {
                        name: "靖江市",
                        id: 1011,
                        upid: 1006
                    },
                    {
                        name: "泰兴市",
                        id: 1012,
                        upid: 1006
                    },
                    {
                        name: "姜堰市",
                        id: 1013,
                        upid: 1006
                    }
                ]
            },
            {
                name: "宿迁市",
                id: 1014,
                upid: 888,
                sub: [
                    {
                        name: "市辖区",
                        id: 1015,
                        upid: 1014
                    },
                    {
                        name: "宿城区",
                        id: 1016,
                        upid: 1014
                    },
                    {
                        name: "宿豫区",
                        id: 1017,
                        upid: 1014
                    },
                    {
                        name: "沭阳县",
                        id: 1018,
                        upid: 1014
                    },
                    {
                        name: "泗阳县",
                        id: 1019,
                        upid: 1014
                    },
                    {
                        name: "泗洪县",
                        id: 1020,
                        upid: 1014
                    }
                ]
            }
        ]
    },
    {
        name: "浙江",
        id: 1021,
        upid: 0,
        sub: [
            {
                name: "杭州市",
                id: 1022,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1023,
                        upid: 1022
                    },
                    {
                        name: "上城区",
                        id: 1024,
                        upid: 1022
                    },
                    {
                        name: "下城区",
                        id: 1025,
                        upid: 1022
                    },
                    {
                        name: "江干区",
                        id: 1026,
                        upid: 1022
                    },
                    {
                        name: "拱墅区",
                        id: 1027,
                        upid: 1022
                    },
                    {
                        name: "西湖区",
                        id: 1028,
                        upid: 1022
                    },
                    {
                        name: "滨江区",
                        id: 1029,
                        upid: 1022
                    },
                    {
                        name: "萧山区",
                        id: 1030,
                        upid: 1022
                    },
                    {
                        name: "余杭区",
                        id: 1031,
                        upid: 1022
                    },
                    {
                        name: "桐庐县",
                        id: 1032,
                        upid: 1022
                    },
                    {
                        name: "淳安县",
                        id: 1033,
                        upid: 1022
                    },
                    {
                        name: "建德市",
                        id: 1034,
                        upid: 1022
                    },
                    {
                        name: "富阳市",
                        id: 1035,
                        upid: 1022
                    },
                    {
                        name: "临安市",
                        id: 1036,
                        upid: 1022
                    }
                ]
            },
            {
                name: "宁波市",
                id: 1037,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1038,
                        upid: 1037
                    },
                    {
                        name: "海曙区",
                        id: 1039,
                        upid: 1037
                    },
                    {
                        name: "江东区",
                        id: 1040,
                        upid: 1037
                    },
                    {
                        name: "江北区",
                        id: 1041,
                        upid: 1037
                    },
                    {
                        name: "北仑区",
                        id: 1042,
                        upid: 1037
                    },
                    {
                        name: "镇海区",
                        id: 1043,
                        upid: 1037
                    },
                    {
                        name: "鄞州区",
                        id: 1044,
                        upid: 1037
                    },
                    {
                        name: "象山县",
                        id: 1045,
                        upid: 1037
                    },
                    {
                        name: "宁海县",
                        id: 1046,
                        upid: 1037
                    },
                    {
                        name: "余姚市",
                        id: 1047,
                        upid: 1037
                    },
                    {
                        name: "慈溪市",
                        id: 1048,
                        upid: 1037
                    },
                    {
                        name: "奉化市",
                        id: 1049,
                        upid: 1037
                    }
                ]
            },
            {
                name: "温州市",
                id: 1050,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1051,
                        upid: 1050
                    },
                    {
                        name: "鹿城区",
                        id: 1052,
                        upid: 1050
                    },
                    {
                        name: "龙湾区",
                        id: 1053,
                        upid: 1050
                    },
                    {
                        name: "瓯海区",
                        id: 1054,
                        upid: 1050
                    },
                    {
                        name: "洞头县",
                        id: 1055,
                        upid: 1050
                    },
                    {
                        name: "永嘉县",
                        id: 1056,
                        upid: 1050
                    },
                    {
                        name: "平阳县",
                        id: 1057,
                        upid: 1050
                    },
                    {
                        name: "苍南县",
                        id: 1058,
                        upid: 1050
                    },
                    {
                        name: "文成县",
                        id: 1059,
                        upid: 1050
                    },
                    {
                        name: "泰顺县",
                        id: 1060,
                        upid: 1050
                    },
                    {
                        name: "瑞安市",
                        id: 1061,
                        upid: 1050
                    },
                    {
                        name: "乐清市",
                        id: 1062,
                        upid: 1050
                    }
                ]
            },
            {
                name: "嘉兴市",
                id: 1063,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1064,
                        upid: 1063
                    },
                    {
                        name: "秀城区",
                        id: 1065,
                        upid: 1063
                    },
                    {
                        name: "秀洲区",
                        id: 1066,
                        upid: 1063
                    },
                    {
                        name: "嘉善县",
                        id: 1067,
                        upid: 1063
                    },
                    {
                        name: "海盐县",
                        id: 1068,
                        upid: 1063
                    },
                    {
                        name: "海宁市",
                        id: 1069,
                        upid: 1063
                    },
                    {
                        name: "平湖市",
                        id: 1070,
                        upid: 1063
                    },
                    {
                        name: "桐乡市",
                        id: 1071,
                        upid: 1063
                    }
                ]
            },
            {
                name: "湖州市",
                id: 1072,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1073,
                        upid: 1072
                    },
                    {
                        name: "吴兴区",
                        id: 1074,
                        upid: 1072
                    },
                    {
                        name: "南浔区",
                        id: 1075,
                        upid: 1072
                    },
                    {
                        name: "德清县",
                        id: 1076,
                        upid: 1072
                    },
                    {
                        name: "长兴县",
                        id: 1077,
                        upid: 1072
                    },
                    {
                        name: "安吉县",
                        id: 1078,
                        upid: 1072
                    }
                ]
            },
            {
                name: "绍兴市",
                id: 1079,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1080,
                        upid: 1079
                    },
                    {
                        name: "越城区",
                        id: 1081,
                        upid: 1079
                    },
                    {
                        name: "绍兴县",
                        id: 1082,
                        upid: 1079
                    },
                    {
                        name: "新昌县",
                        id: 1083,
                        upid: 1079
                    },
                    {
                        name: "诸暨市",
                        id: 1084,
                        upid: 1079
                    },
                    {
                        name: "上虞市",
                        id: 1085,
                        upid: 1079
                    },
                    {
                        name: "嵊州市",
                        id: 1086,
                        upid: 1079
                    }
                ]
            },
            {
                name: "金华市",
                id: 1087,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1088,
                        upid: 1087
                    },
                    {
                        name: "婺城区",
                        id: 1089,
                        upid: 1087
                    },
                    {
                        name: "金东区",
                        id: 1090,
                        upid: 1087
                    },
                    {
                        name: "武义县",
                        id: 1091,
                        upid: 1087
                    },
                    {
                        name: "浦江县",
                        id: 1092,
                        upid: 1087
                    },
                    {
                        name: "磐安县",
                        id: 1093,
                        upid: 1087
                    },
                    {
                        name: "兰溪市",
                        id: 1094,
                        upid: 1087
                    },
                    {
                        name: "义乌市",
                        id: 1095,
                        upid: 1087
                    },
                    {
                        name: "东阳市",
                        id: 1096,
                        upid: 1087
                    },
                    {
                        name: "永康市",
                        id: 1097,
                        upid: 1087
                    }
                ]
            },
            {
                name: "衢州市",
                id: 1098,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1099,
                        upid: 1098
                    },
                    {
                        name: "柯城区",
                        id: 1100,
                        upid: 1098
                    },
                    {
                        name: "衢江区",
                        id: 1101,
                        upid: 1098
                    },
                    {
                        name: "常山县",
                        id: 1102,
                        upid: 1098
                    },
                    {
                        name: "开化县",
                        id: 1103,
                        upid: 1098
                    },
                    {
                        name: "龙游县",
                        id: 1104,
                        upid: 1098
                    },
                    {
                        name: "江山市",
                        id: 1105,
                        upid: 1098
                    }
                ]
            },
            {
                name: "舟山市",
                id: 1106,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1107,
                        upid: 1106
                    },
                    {
                        name: "定海区",
                        id: 1108,
                        upid: 1106
                    },
                    {
                        name: "普陀区",
                        id: 1109,
                        upid: 1106
                    },
                    {
                        name: "岱山县",
                        id: 1110,
                        upid: 1106
                    },
                    {
                        name: "嵊泗县",
                        id: 1111,
                        upid: 1106
                    }
                ]
            },
            {
                name: "台州市",
                id: 1112,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1113,
                        upid: 1112
                    },
                    {
                        name: "椒江区",
                        id: 1114,
                        upid: 1112
                    },
                    {
                        name: "黄岩区",
                        id: 1115,
                        upid: 1112
                    },
                    {
                        name: "路桥区",
                        id: 1116,
                        upid: 1112
                    },
                    {
                        name: "玉环县",
                        id: 1117,
                        upid: 1112
                    },
                    {
                        name: "三门县",
                        id: 1118,
                        upid: 1112
                    },
                    {
                        name: "天台县",
                        id: 1119,
                        upid: 1112
                    },
                    {
                        name: "仙居县",
                        id: 1120,
                        upid: 1112
                    },
                    {
                        name: "温岭市",
                        id: 1121,
                        upid: 1112
                    },
                    {
                        name: "临海市",
                        id: 1122,
                        upid: 1112
                    }
                ]
            },
            {
                name: "丽水市",
                id: 1123,
                upid: 1021,
                sub: [
                    {
                        name: "市辖区",
                        id: 1124,
                        upid: 1123
                    },
                    {
                        name: "莲都区",
                        id: 1125,
                        upid: 1123
                    },
                    {
                        name: "青田县",
                        id: 1126,
                        upid: 1123
                    },
                    {
                        name: "缙云县",
                        id: 1127,
                        upid: 1123
                    },
                    {
                        name: "遂昌县",
                        id: 1128,
                        upid: 1123
                    },
                    {
                        name: "松阳县",
                        id: 1129,
                        upid: 1123
                    },
                    {
                        name: "云和县",
                        id: 1130,
                        upid: 1123
                    },
                    {
                        name: "庆元县",
                        id: 1131,
                        upid: 1123
                    },
                    {
                        name: "景宁畲族自治县",
                        id: 1132,
                        upid: 1123
                    },
                    {
                        name: "龙泉市",
                        id: 1133,
                        upid: 1123
                    }
                ]
            }
        ]
    },
    {
        name: "安徽",
        id: 1134,
        upid: 0,
        sub: [
            {
                name: "合肥市",
                id: 1135,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1136,
                        upid: 1135
                    },
                    {
                        name: "瑶海区",
                        id: 1137,
                        upid: 1135
                    },
                    {
                        name: "庐阳区",
                        id: 1138,
                        upid: 1135
                    },
                    {
                        name: "蜀山区",
                        id: 1139,
                        upid: 1135
                    },
                    {
                        name: "包河区",
                        id: 1140,
                        upid: 1135
                    },
                    {
                        name: "长丰县",
                        id: 1141,
                        upid: 1135
                    },
                    {
                        name: "肥东县",
                        id: 1142,
                        upid: 1135
                    },
                    {
                        name: "肥西县",
                        id: 1143,
                        upid: 1135
                    }
                ]
            },
            {
                name: "芜湖市",
                id: 1144,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1145,
                        upid: 1144
                    },
                    {
                        name: "镜湖区",
                        id: 1146,
                        upid: 1144
                    },
                    {
                        name: "马塘区",
                        id: 1147,
                        upid: 1144
                    },
                    {
                        name: "新芜区",
                        id: 1148,
                        upid: 1144
                    },
                    {
                        name: "鸠江区",
                        id: 1149,
                        upid: 1144
                    },
                    {
                        name: "芜湖县",
                        id: 1150,
                        upid: 1144
                    },
                    {
                        name: "繁昌县",
                        id: 1151,
                        upid: 1144
                    },
                    {
                        name: "南陵县",
                        id: 1152,
                        upid: 1144
                    }
                ]
            },
            {
                name: "蚌埠市",
                id: 1153,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1154,
                        upid: 1153
                    },
                    {
                        name: "龙子湖区",
                        id: 1155,
                        upid: 1153
                    },
                    {
                        name: "蚌山区",
                        id: 1156,
                        upid: 1153
                    },
                    {
                        name: "禹会区",
                        id: 1157,
                        upid: 1153
                    },
                    {
                        name: "淮上区",
                        id: 1158,
                        upid: 1153
                    },
                    {
                        name: "怀远县",
                        id: 1159,
                        upid: 1153
                    },
                    {
                        name: "五河县",
                        id: 1160,
                        upid: 1153
                    },
                    {
                        name: "固镇县",
                        id: 1161,
                        upid: 1153
                    }
                ]
            },
            {
                name: "淮南市",
                id: 1162,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1163,
                        upid: 1162
                    },
                    {
                        name: "大通区",
                        id: 1164,
                        upid: 1162
                    },
                    {
                        name: "田家庵区",
                        id: 1165,
                        upid: 1162
                    },
                    {
                        name: "谢家集区",
                        id: 1166,
                        upid: 1162
                    },
                    {
                        name: "八公山区",
                        id: 1167,
                        upid: 1162
                    },
                    {
                        name: "潘集区",
                        id: 1168,
                        upid: 1162
                    },
                    {
                        name: "凤台县",
                        id: 1169,
                        upid: 1162
                    }
                ]
            },
            {
                name: "马鞍山市",
                id: 1170,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1171,
                        upid: 1170
                    },
                    {
                        name: "金家庄区",
                        id: 1172,
                        upid: 1170
                    },
                    {
                        name: "花山区",
                        id: 1173,
                        upid: 1170
                    },
                    {
                        name: "雨山区",
                        id: 1174,
                        upid: 1170
                    },
                    {
                        name: "当涂县",
                        id: 1175,
                        upid: 1170
                    }
                ]
            },
            {
                name: "淮北市",
                id: 1176,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1177,
                        upid: 1176
                    },
                    {
                        name: "杜集区",
                        id: 1178,
                        upid: 1176
                    },
                    {
                        name: "相山区",
                        id: 1179,
                        upid: 1176
                    },
                    {
                        name: "烈山区",
                        id: 1180,
                        upid: 1176
                    },
                    {
                        name: "濉溪县",
                        id: 1181,
                        upid: 1176
                    }
                ]
            },
            {
                name: "铜陵市",
                id: 1182,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1183,
                        upid: 1182
                    },
                    {
                        name: "铜官山区",
                        id: 1184,
                        upid: 1182
                    },
                    {
                        name: "狮子山区",
                        id: 1185,
                        upid: 1182
                    },
                    {
                        name: "郊区",
                        id: 1186,
                        upid: 1182
                    },
                    {
                        name: "铜陵县",
                        id: 1187,
                        upid: 1182
                    }
                ]
            },
            {
                name: "安庆市",
                id: 1188,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1189,
                        upid: 1188
                    },
                    {
                        name: "迎江区",
                        id: 1190,
                        upid: 1188
                    },
                    {
                        name: "大观区",
                        id: 1191,
                        upid: 1188
                    },
                    {
                        name: "郊区",
                        id: 1192,
                        upid: 1188
                    },
                    {
                        name: "怀宁县",
                        id: 1193,
                        upid: 1188
                    },
                    {
                        name: "枞阳县",
                        id: 1194,
                        upid: 1188
                    },
                    {
                        name: "潜山县",
                        id: 1195,
                        upid: 1188
                    },
                    {
                        name: "太湖县",
                        id: 1196,
                        upid: 1188
                    },
                    {
                        name: "宿松县",
                        id: 1197,
                        upid: 1188
                    },
                    {
                        name: "望江县",
                        id: 1198,
                        upid: 1188
                    },
                    {
                        name: "岳西县",
                        id: 1199,
                        upid: 1188
                    },
                    {
                        name: "桐城市",
                        id: 1200,
                        upid: 1188
                    }
                ]
            },
            {
                name: "黄山市",
                id: 1201,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1202,
                        upid: 1201
                    },
                    {
                        name: "屯溪区",
                        id: 1203,
                        upid: 1201
                    },
                    {
                        name: "黄山区",
                        id: 1204,
                        upid: 1201
                    },
                    {
                        name: "徽州区",
                        id: 1205,
                        upid: 1201
                    },
                    {
                        name: "歙县",
                        id: 1206,
                        upid: 1201
                    },
                    {
                        name: "休宁县",
                        id: 1207,
                        upid: 1201
                    },
                    {
                        name: "黟县",
                        id: 1208,
                        upid: 1201
                    },
                    {
                        name: "祁门县",
                        id: 1209,
                        upid: 1201
                    }
                ]
            },
            {
                name: "滁州市",
                id: 1210,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1211,
                        upid: 1210
                    },
                    {
                        name: "琅琊区",
                        id: 1212,
                        upid: 1210
                    },
                    {
                        name: "南谯区",
                        id: 1213,
                        upid: 1210
                    },
                    {
                        name: "来安县",
                        id: 1214,
                        upid: 1210
                    },
                    {
                        name: "全椒县",
                        id: 1215,
                        upid: 1210
                    },
                    {
                        name: "定远县",
                        id: 1216,
                        upid: 1210
                    },
                    {
                        name: "凤阳县",
                        id: 1217,
                        upid: 1210
                    },
                    {
                        name: "天长市",
                        id: 1218,
                        upid: 1210
                    },
                    {
                        name: "明光市",
                        id: 1219,
                        upid: 1210
                    }
                ]
            },
            {
                name: "阜阳市",
                id: 1220,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1221,
                        upid: 1220
                    },
                    {
                        name: "颍州区",
                        id: 1222,
                        upid: 1220
                    },
                    {
                        name: "颍东区",
                        id: 1223,
                        upid: 1220
                    },
                    {
                        name: "颍泉区",
                        id: 1224,
                        upid: 1220
                    },
                    {
                        name: "临泉县",
                        id: 1225,
                        upid: 1220
                    },
                    {
                        name: "太和县",
                        id: 1226,
                        upid: 1220
                    },
                    {
                        name: "阜南县",
                        id: 1227,
                        upid: 1220
                    },
                    {
                        name: "颍上县",
                        id: 1228,
                        upid: 1220
                    },
                    {
                        name: "界首市",
                        id: 1229,
                        upid: 1220
                    }
                ]
            },
            {
                name: "宿州市",
                id: 1230,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1231,
                        upid: 1230
                    },
                    {
                        name: "墉桥区",
                        id: 1232,
                        upid: 1230
                    },
                    {
                        name: "砀山县",
                        id: 1233,
                        upid: 1230
                    },
                    {
                        name: "萧县",
                        id: 1234,
                        upid: 1230
                    },
                    {
                        name: "灵璧县",
                        id: 1235,
                        upid: 1230
                    },
                    {
                        name: "泗县",
                        id: 1236,
                        upid: 1230
                    }
                ]
            },
            {
                name: "巢湖市",
                id: 1237,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1238,
                        upid: 1237
                    },
                    {
                        name: "居巢区",
                        id: 1239,
                        upid: 1237
                    },
                    {
                        name: "庐江县",
                        id: 1240,
                        upid: 1237
                    },
                    {
                        name: "无为县",
                        id: 1241,
                        upid: 1237
                    },
                    {
                        name: "含山县",
                        id: 1242,
                        upid: 1237
                    },
                    {
                        name: "和县",
                        id: 1243,
                        upid: 1237
                    }
                ]
            },
            {
                name: "六安市",
                id: 1244,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1245,
                        upid: 1244
                    },
                    {
                        name: "金安区",
                        id: 1246,
                        upid: 1244
                    },
                    {
                        name: "裕安区",
                        id: 1247,
                        upid: 1244
                    },
                    {
                        name: "寿县",
                        id: 1248,
                        upid: 1244
                    },
                    {
                        name: "霍邱县",
                        id: 1249,
                        upid: 1244
                    },
                    {
                        name: "舒城县",
                        id: 1250,
                        upid: 1244
                    },
                    {
                        name: "金寨县",
                        id: 1251,
                        upid: 1244
                    },
                    {
                        name: "霍山县",
                        id: 1252,
                        upid: 1244
                    }
                ]
            },
            {
                name: "亳州市",
                id: 1253,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1254,
                        upid: 1253
                    },
                    {
                        name: "谯城区",
                        id: 1255,
                        upid: 1253
                    },
                    {
                        name: "涡阳县",
                        id: 1256,
                        upid: 1253
                    },
                    {
                        name: "蒙城县",
                        id: 1257,
                        upid: 1253
                    },
                    {
                        name: "利辛县",
                        id: 1258,
                        upid: 1253
                    }
                ]
            },
            {
                name: "池州市",
                id: 1259,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1260,
                        upid: 1259
                    },
                    {
                        name: "贵池区",
                        id: 1261,
                        upid: 1259
                    },
                    {
                        name: "东至县",
                        id: 1262,
                        upid: 1259
                    },
                    {
                        name: "石台县",
                        id: 1263,
                        upid: 1259
                    },
                    {
                        name: "青阳县",
                        id: 1264,
                        upid: 1259
                    }
                ]
            },
            {
                name: "宣城市",
                id: 1265,
                upid: 1134,
                sub: [
                    {
                        name: "市辖区",
                        id: 1266,
                        upid: 1265
                    },
                    {
                        name: "宣州区",
                        id: 1267,
                        upid: 1265
                    },
                    {
                        name: "郎溪县",
                        id: 1268,
                        upid: 1265
                    },
                    {
                        name: "广德县",
                        id: 1269,
                        upid: 1265
                    },
                    {
                        name: "泾县",
                        id: 1270,
                        upid: 1265
                    },
                    {
                        name: "绩溪县",
                        id: 1271,
                        upid: 1265
                    },
                    {
                        name: "旌德县",
                        id: 1272,
                        upid: 1265
                    },
                    {
                        name: "宁国市",
                        id: 1273,
                        upid: 1265
                    }
                ]
            }
        ]
    },
    {
        name: "福建",
        id: 1274,
        upid: 0,
        sub: [
            {
                name: "福州市",
                id: 1275,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1276,
                        upid: 1275
                    },
                    {
                        name: "鼓楼区",
                        id: 1277,
                        upid: 1275
                    },
                    {
                        name: "台江区",
                        id: 1278,
                        upid: 1275
                    },
                    {
                        name: "仓山区",
                        id: 1279,
                        upid: 1275
                    },
                    {
                        name: "马尾区",
                        id: 1280,
                        upid: 1275
                    },
                    {
                        name: "晋安区",
                        id: 1281,
                        upid: 1275
                    },
                    {
                        name: "闽侯县",
                        id: 1282,
                        upid: 1275
                    },
                    {
                        name: "连江县",
                        id: 1283,
                        upid: 1275
                    },
                    {
                        name: "罗源县",
                        id: 1284,
                        upid: 1275
                    },
                    {
                        name: "闽清县",
                        id: 1285,
                        upid: 1275
                    },
                    {
                        name: "永泰县",
                        id: 1286,
                        upid: 1275
                    },
                    {
                        name: "平潭县",
                        id: 1287,
                        upid: 1275
                    },
                    {
                        name: "福清市",
                        id: 1288,
                        upid: 1275
                    },
                    {
                        name: "长乐市",
                        id: 1289,
                        upid: 1275
                    }
                ]
            },
            {
                name: "厦门市",
                id: 1290,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1291,
                        upid: 1290
                    },
                    {
                        name: "思明区",
                        id: 1292,
                        upid: 1290
                    },
                    {
                        name: "海沧区",
                        id: 1293,
                        upid: 1290
                    },
                    {
                        name: "湖里区",
                        id: 1294,
                        upid: 1290
                    },
                    {
                        name: "集美区",
                        id: 1295,
                        upid: 1290
                    },
                    {
                        name: "同安区",
                        id: 1296,
                        upid: 1290
                    },
                    {
                        name: "翔安区",
                        id: 1297,
                        upid: 1290
                    }
                ]
            },
            {
                name: "莆田市",
                id: 1298,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1299,
                        upid: 1298
                    },
                    {
                        name: "城厢区",
                        id: 1300,
                        upid: 1298
                    },
                    {
                        name: "涵江区",
                        id: 1301,
                        upid: 1298
                    },
                    {
                        name: "荔城区",
                        id: 1302,
                        upid: 1298
                    },
                    {
                        name: "秀屿区",
                        id: 1303,
                        upid: 1298
                    },
                    {
                        name: "仙游县",
                        id: 1304,
                        upid: 1298
                    }
                ]
            },
            {
                name: "三明市",
                id: 1305,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1306,
                        upid: 1305
                    },
                    {
                        name: "梅列区",
                        id: 1307,
                        upid: 1305
                    },
                    {
                        name: "三元区",
                        id: 1308,
                        upid: 1305
                    },
                    {
                        name: "明溪县",
                        id: 1309,
                        upid: 1305
                    },
                    {
                        name: "清流县",
                        id: 1310,
                        upid: 1305
                    },
                    {
                        name: "宁化县",
                        id: 1311,
                        upid: 1305
                    },
                    {
                        name: "大田县",
                        id: 1312,
                        upid: 1305
                    },
                    {
                        name: "尤溪县",
                        id: 1313,
                        upid: 1305
                    },
                    {
                        name: "沙县",
                        id: 1314,
                        upid: 1305
                    },
                    {
                        name: "将乐县",
                        id: 1315,
                        upid: 1305
                    },
                    {
                        name: "泰宁县",
                        id: 1316,
                        upid: 1305
                    },
                    {
                        name: "建宁县",
                        id: 1317,
                        upid: 1305
                    },
                    {
                        name: "永安市",
                        id: 1318,
                        upid: 1305
                    }
                ]
            },
            {
                name: "泉州市",
                id: 1319,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1320,
                        upid: 1319
                    },
                    {
                        name: "鲤城区",
                        id: 1321,
                        upid: 1319
                    },
                    {
                        name: "丰泽区",
                        id: 1322,
                        upid: 1319
                    },
                    {
                        name: "洛江区",
                        id: 1323,
                        upid: 1319
                    },
                    {
                        name: "泉港区",
                        id: 1324,
                        upid: 1319
                    },
                    {
                        name: "惠安县",
                        id: 1325,
                        upid: 1319
                    },
                    {
                        name: "安溪县",
                        id: 1326,
                        upid: 1319
                    },
                    {
                        name: "永春县",
                        id: 1327,
                        upid: 1319
                    },
                    {
                        name: "德化县",
                        id: 1328,
                        upid: 1319
                    },
                    {
                        name: "金门县",
                        id: 1329,
                        upid: 1319
                    },
                    {
                        name: "石狮市",
                        id: 1330,
                        upid: 1319
                    },
                    {
                        name: "晋江市",
                        id: 1331,
                        upid: 1319
                    },
                    {
                        name: "南安市",
                        id: 1332,
                        upid: 1319
                    }
                ]
            },
            {
                name: "漳州市",
                id: 1333,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1334,
                        upid: 1333
                    },
                    {
                        name: "芗城区",
                        id: 1335,
                        upid: 1333
                    },
                    {
                        name: "龙文区",
                        id: 1336,
                        upid: 1333
                    },
                    {
                        name: "云霄县",
                        id: 1337,
                        upid: 1333
                    },
                    {
                        name: "漳浦县",
                        id: 1338,
                        upid: 1333
                    },
                    {
                        name: "诏安县",
                        id: 1339,
                        upid: 1333
                    },
                    {
                        name: "长泰县",
                        id: 1340,
                        upid: 1333
                    },
                    {
                        name: "东山县",
                        id: 1341,
                        upid: 1333
                    },
                    {
                        name: "南靖县",
                        id: 1342,
                        upid: 1333
                    },
                    {
                        name: "平和县",
                        id: 1343,
                        upid: 1333
                    },
                    {
                        name: "华安县",
                        id: 1344,
                        upid: 1333
                    },
                    {
                        name: "龙海市",
                        id: 1345,
                        upid: 1333
                    }
                ]
            },
            {
                name: "南平市",
                id: 1346,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1347,
                        upid: 1346
                    },
                    {
                        name: "延平区",
                        id: 1348,
                        upid: 1346
                    },
                    {
                        name: "顺昌县",
                        id: 1349,
                        upid: 1346
                    },
                    {
                        name: "浦城县",
                        id: 1350,
                        upid: 1346
                    },
                    {
                        name: "光泽县",
                        id: 1351,
                        upid: 1346
                    },
                    {
                        name: "松溪县",
                        id: 1352,
                        upid: 1346
                    },
                    {
                        name: "政和县",
                        id: 1353,
                        upid: 1346
                    },
                    {
                        name: "邵武市",
                        id: 1354,
                        upid: 1346
                    },
                    {
                        name: "武夷山市",
                        id: 1355,
                        upid: 1346
                    },
                    {
                        name: "建瓯市",
                        id: 1356,
                        upid: 1346
                    },
                    {
                        name: "建阳市",
                        id: 1357,
                        upid: 1346
                    }
                ]
            },
            {
                name: "龙岩市",
                id: 1358,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1359,
                        upid: 1358
                    },
                    {
                        name: "新罗区",
                        id: 1360,
                        upid: 1358
                    },
                    {
                        name: "长汀县",
                        id: 1361,
                        upid: 1358
                    },
                    {
                        name: "永定县",
                        id: 1362,
                        upid: 1358
                    },
                    {
                        name: "上杭县",
                        id: 1363,
                        upid: 1358
                    },
                    {
                        name: "武平县",
                        id: 1364,
                        upid: 1358
                    },
                    {
                        name: "连城县",
                        id: 1365,
                        upid: 1358
                    },
                    {
                        name: "漳平市",
                        id: 1366,
                        upid: 1358
                    }
                ]
            },
            {
                name: "宁德市",
                id: 1367,
                upid: 1274,
                sub: [
                    {
                        name: "市辖区",
                        id: 1368,
                        upid: 1367
                    },
                    {
                        name: "蕉城区",
                        id: 1369,
                        upid: 1367
                    },
                    {
                        name: "霞浦县",
                        id: 1370,
                        upid: 1367
                    },
                    {
                        name: "古田县",
                        id: 1371,
                        upid: 1367
                    },
                    {
                        name: "屏南县",
                        id: 1372,
                        upid: 1367
                    },
                    {
                        name: "寿宁县",
                        id: 1373,
                        upid: 1367
                    },
                    {
                        name: "周宁县",
                        id: 1374,
                        upid: 1367
                    },
                    {
                        name: "柘荣县",
                        id: 1375,
                        upid: 1367
                    },
                    {
                        name: "福安市",
                        id: 1376,
                        upid: 1367
                    },
                    {
                        name: "福鼎市",
                        id: 1377,
                        upid: 1367
                    }
                ]
            }
        ]
    },
    {
        name: "江西",
        id: 1378,
        upid: 0,
        sub: [
            {
                name: "南昌市",
                id: 1379,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1380,
                        upid: 1379
                    },
                    {
                        name: "东湖区",
                        id: 1381,
                        upid: 1379
                    },
                    {
                        name: "西湖区",
                        id: 1382,
                        upid: 1379
                    },
                    {
                        name: "青云谱区",
                        id: 1383,
                        upid: 1379
                    },
                    {
                        name: "湾里区",
                        id: 1384,
                        upid: 1379
                    },
                    {
                        name: "青山湖区",
                        id: 1385,
                        upid: 1379
                    },
                    {
                        name: "南昌县",
                        id: 1386,
                        upid: 1379
                    },
                    {
                        name: "新建县",
                        id: 1387,
                        upid: 1379
                    },
                    {
                        name: "安义县",
                        id: 1388,
                        upid: 1379
                    },
                    {
                        name: "进贤县",
                        id: 1389,
                        upid: 1379
                    }
                ]
            },
            {
                name: "景德镇市",
                id: 1390,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1391,
                        upid: 1390
                    },
                    {
                        name: "昌江区",
                        id: 1392,
                        upid: 1390
                    },
                    {
                        name: "珠山区",
                        id: 1393,
                        upid: 1390
                    },
                    {
                        name: "浮梁县",
                        id: 1394,
                        upid: 1390
                    },
                    {
                        name: "乐平市",
                        id: 1395,
                        upid: 1390
                    }
                ]
            },
            {
                name: "萍乡市",
                id: 1396,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1397,
                        upid: 1396
                    },
                    {
                        name: "安源区",
                        id: 1398,
                        upid: 1396
                    },
                    {
                        name: "湘东区",
                        id: 1399,
                        upid: 1396
                    },
                    {
                        name: "莲花县",
                        id: 1400,
                        upid: 1396
                    },
                    {
                        name: "上栗县",
                        id: 1401,
                        upid: 1396
                    },
                    {
                        name: "芦溪县",
                        id: 1402,
                        upid: 1396
                    }
                ]
            },
            {
                name: "九江市",
                id: 1403,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1404,
                        upid: 1403
                    },
                    {
                        name: "庐山区",
                        id: 1405,
                        upid: 1403
                    },
                    {
                        name: "浔阳区",
                        id: 1406,
                        upid: 1403
                    },
                    {
                        name: "九江县",
                        id: 1407,
                        upid: 1403
                    },
                    {
                        name: "武宁县",
                        id: 1408,
                        upid: 1403
                    },
                    {
                        name: "修水县",
                        id: 1409,
                        upid: 1403
                    },
                    {
                        name: "永修县",
                        id: 1410,
                        upid: 1403
                    },
                    {
                        name: "德安县",
                        id: 1411,
                        upid: 1403
                    },
                    {
                        name: "星子县",
                        id: 1412,
                        upid: 1403
                    },
                    {
                        name: "都昌县",
                        id: 1413,
                        upid: 1403
                    },
                    {
                        name: "湖口县",
                        id: 1414,
                        upid: 1403
                    },
                    {
                        name: "彭泽县",
                        id: 1415,
                        upid: 1403
                    },
                    {
                        name: "瑞昌市",
                        id: 1416,
                        upid: 1403
                    }
                ]
            },
            {
                name: "新余市",
                id: 1417,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1418,
                        upid: 1417
                    },
                    {
                        name: "渝水区",
                        id: 1419,
                        upid: 1417
                    },
                    {
                        name: "分宜县",
                        id: 1420,
                        upid: 1417
                    }
                ]
            },
            {
                name: "鹰潭市",
                id: 1421,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1422,
                        upid: 1421
                    },
                    {
                        name: "月湖区",
                        id: 1423,
                        upid: 1421
                    },
                    {
                        name: "余江县",
                        id: 1424,
                        upid: 1421
                    },
                    {
                        name: "贵溪市",
                        id: 1425,
                        upid: 1421
                    }
                ]
            },
            {
                name: "赣州市",
                id: 1426,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1427,
                        upid: 1426
                    },
                    {
                        name: "章贡区",
                        id: 1428,
                        upid: 1426
                    },
                    {
                        name: "赣县",
                        id: 1429,
                        upid: 1426
                    },
                    {
                        name: "信丰县",
                        id: 1430,
                        upid: 1426
                    },
                    {
                        name: "大余县",
                        id: 1431,
                        upid: 1426
                    },
                    {
                        name: "上犹县",
                        id: 1432,
                        upid: 1426
                    },
                    {
                        name: "崇义县",
                        id: 1433,
                        upid: 1426
                    },
                    {
                        name: "安远县",
                        id: 1434,
                        upid: 1426
                    },
                    {
                        name: "龙南县",
                        id: 1435,
                        upid: 1426
                    },
                    {
                        name: "定南县",
                        id: 1436,
                        upid: 1426
                    },
                    {
                        name: "全南县",
                        id: 1437,
                        upid: 1426
                    },
                    {
                        name: "宁都县",
                        id: 1438,
                        upid: 1426
                    },
                    {
                        name: "于都县",
                        id: 1439,
                        upid: 1426
                    },
                    {
                        name: "兴国县",
                        id: 1440,
                        upid: 1426
                    },
                    {
                        name: "会昌县",
                        id: 1441,
                        upid: 1426
                    },
                    {
                        name: "寻乌县",
                        id: 1442,
                        upid: 1426
                    },
                    {
                        name: "石城县",
                        id: 1443,
                        upid: 1426
                    },
                    {
                        name: "瑞金市",
                        id: 1444,
                        upid: 1426
                    },
                    {
                        name: "南康市",
                        id: 1445,
                        upid: 1426
                    }
                ]
            },
            {
                name: "吉安市",
                id: 1446,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1447,
                        upid: 1446
                    },
                    {
                        name: "吉州区",
                        id: 1448,
                        upid: 1446
                    },
                    {
                        name: "青原区",
                        id: 1449,
                        upid: 1446
                    },
                    {
                        name: "吉安县",
                        id: 1450,
                        upid: 1446
                    },
                    {
                        name: "吉水县",
                        id: 1451,
                        upid: 1446
                    },
                    {
                        name: "峡江县",
                        id: 1452,
                        upid: 1446
                    },
                    {
                        name: "新干县",
                        id: 1453,
                        upid: 1446
                    },
                    {
                        name: "永丰县",
                        id: 1454,
                        upid: 1446
                    },
                    {
                        name: "泰和县",
                        id: 1455,
                        upid: 1446
                    },
                    {
                        name: "遂川县",
                        id: 1456,
                        upid: 1446
                    },
                    {
                        name: "万安县",
                        id: 1457,
                        upid: 1446
                    },
                    {
                        name: "安福县",
                        id: 1458,
                        upid: 1446
                    },
                    {
                        name: "永新县",
                        id: 1459,
                        upid: 1446
                    },
                    {
                        name: "井冈山市",
                        id: 1460,
                        upid: 1446
                    }
                ]
            },
            {
                name: "宜春市",
                id: 1461,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1462,
                        upid: 1461
                    },
                    {
                        name: "袁州区",
                        id: 1463,
                        upid: 1461
                    },
                    {
                        name: "奉新县",
                        id: 1464,
                        upid: 1461
                    },
                    {
                        name: "万载县",
                        id: 1465,
                        upid: 1461
                    },
                    {
                        name: "上高县",
                        id: 1466,
                        upid: 1461
                    },
                    {
                        name: "宜丰县",
                        id: 1467,
                        upid: 1461
                    },
                    {
                        name: "靖安县",
                        id: 1468,
                        upid: 1461
                    },
                    {
                        name: "铜鼓县",
                        id: 1469,
                        upid: 1461
                    },
                    {
                        name: "丰城市",
                        id: 1470,
                        upid: 1461
                    },
                    {
                        name: "樟树市",
                        id: 1471,
                        upid: 1461
                    },
                    {
                        name: "高安市",
                        id: 1472,
                        upid: 1461
                    }
                ]
            },
            {
                name: "抚州市",
                id: 1473,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1474,
                        upid: 1473
                    },
                    {
                        name: "临川区",
                        id: 1475,
                        upid: 1473
                    },
                    {
                        name: "南城县",
                        id: 1476,
                        upid: 1473
                    },
                    {
                        name: "黎川县",
                        id: 1477,
                        upid: 1473
                    },
                    {
                        name: "南丰县",
                        id: 1478,
                        upid: 1473
                    },
                    {
                        name: "崇仁县",
                        id: 1479,
                        upid: 1473
                    },
                    {
                        name: "乐安县",
                        id: 1480,
                        upid: 1473
                    },
                    {
                        name: "宜黄县",
                        id: 1481,
                        upid: 1473
                    },
                    {
                        name: "金溪县",
                        id: 1482,
                        upid: 1473
                    },
                    {
                        name: "资溪县",
                        id: 1483,
                        upid: 1473
                    },
                    {
                        name: "东乡县",
                        id: 1484,
                        upid: 1473
                    },
                    {
                        name: "广昌县",
                        id: 1485,
                        upid: 1473
                    }
                ]
            },
            {
                name: "上饶市",
                id: 1486,
                upid: 1378,
                sub: [
                    {
                        name: "市辖区",
                        id: 1487,
                        upid: 1486
                    },
                    {
                        name: "信州区",
                        id: 1488,
                        upid: 1486
                    },
                    {
                        name: "上饶县",
                        id: 1489,
                        upid: 1486
                    },
                    {
                        name: "广丰县",
                        id: 1490,
                        upid: 1486
                    },
                    {
                        name: "玉山县",
                        id: 1491,
                        upid: 1486
                    },
                    {
                        name: "铅山县",
                        id: 1492,
                        upid: 1486
                    },
                    {
                        name: "横峰县",
                        id: 1493,
                        upid: 1486
                    },
                    {
                        name: "弋阳县",
                        id: 1494,
                        upid: 1486
                    },
                    {
                        name: "余干县",
                        id: 1495,
                        upid: 1486
                    },
                    {
                        name: "鄱阳县",
                        id: 1496,
                        upid: 1486
                    },
                    {
                        name: "万年县",
                        id: 1497,
                        upid: 1486
                    },
                    {
                        name: "婺源县",
                        id: 1498,
                        upid: 1486
                    },
                    {
                        name: "德兴市",
                        id: 1499,
                        upid: 1486
                    }
                ]
            }
        ]
    },
    {
        name: "山东",
        id: 1500,
        upid: 0,
        sub: [
            {
                name: "济南市",
                id: 1501,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1502,
                        upid: 1501
                    },
                    {
                        name: "历下区",
                        id: 1503,
                        upid: 1501
                    },
                    {
                        name: "市中区",
                        id: 1504,
                        upid: 1501
                    },
                    {
                        name: "槐荫区",
                        id: 1505,
                        upid: 1501
                    },
                    {
                        name: "天桥区",
                        id: 1506,
                        upid: 1501
                    },
                    {
                        name: "历城区",
                        id: 1507,
                        upid: 1501
                    },
                    {
                        name: "长清区",
                        id: 1508,
                        upid: 1501
                    },
                    {
                        name: "平阴县",
                        id: 1509,
                        upid: 1501
                    },
                    {
                        name: "济阳县",
                        id: 1510,
                        upid: 1501
                    },
                    {
                        name: "商河县",
                        id: 1511,
                        upid: 1501
                    },
                    {
                        name: "章丘市",
                        id: 1512,
                        upid: 1501
                    }
                ]
            },
            {
                name: "青岛市",
                id: 1513,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1514,
                        upid: 1513
                    },
                    {
                        name: "市南区",
                        id: 1515,
                        upid: 1513
                    },
                    {
                        name: "市北区",
                        id: 1516,
                        upid: 1513
                    },
                    {
                        name: "四方区",
                        id: 1517,
                        upid: 1513
                    },
                    {
                        name: "黄岛区",
                        id: 1518,
                        upid: 1513
                    },
                    {
                        name: "崂山区",
                        id: 1519,
                        upid: 1513
                    },
                    {
                        name: "李沧区",
                        id: 1520,
                        upid: 1513
                    },
                    {
                        name: "城阳区",
                        id: 1521,
                        upid: 1513
                    },
                    {
                        name: "胶州市",
                        id: 1522,
                        upid: 1513
                    },
                    {
                        name: "即墨市",
                        id: 1523,
                        upid: 1513
                    },
                    {
                        name: "平度市",
                        id: 1524,
                        upid: 1513
                    },
                    {
                        name: "胶南市",
                        id: 1525,
                        upid: 1513
                    },
                    {
                        name: "莱西市",
                        id: 1526,
                        upid: 1513
                    }
                ]
            },
            {
                name: "淄博市",
                id: 1527,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1528,
                        upid: 1527
                    },
                    {
                        name: "淄川区",
                        id: 1529,
                        upid: 1527
                    },
                    {
                        name: "张店区",
                        id: 1530,
                        upid: 1527
                    },
                    {
                        name: "博山区",
                        id: 1531,
                        upid: 1527
                    },
                    {
                        name: "临淄区",
                        id: 1532,
                        upid: 1527
                    },
                    {
                        name: "周村区",
                        id: 1533,
                        upid: 1527
                    },
                    {
                        name: "桓台县",
                        id: 1534,
                        upid: 1527
                    },
                    {
                        name: "高青县",
                        id: 1535,
                        upid: 1527
                    },
                    {
                        name: "沂源县",
                        id: 1536,
                        upid: 1527
                    }
                ]
            },
            {
                name: "枣庄市",
                id: 1537,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1538,
                        upid: 1537
                    },
                    {
                        name: "市中区",
                        id: 1539,
                        upid: 1537
                    },
                    {
                        name: "薛城区",
                        id: 1540,
                        upid: 1537
                    },
                    {
                        name: "峄城区",
                        id: 1541,
                        upid: 1537
                    },
                    {
                        name: "台儿庄区",
                        id: 1542,
                        upid: 1537
                    },
                    {
                        name: "山亭区",
                        id: 1543,
                        upid: 1537
                    },
                    {
                        name: "滕州市",
                        id: 1544,
                        upid: 1537
                    }
                ]
            },
            {
                name: "东营市",
                id: 1545,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1546,
                        upid: 1545
                    },
                    {
                        name: "东营区",
                        id: 1547,
                        upid: 1545
                    },
                    {
                        name: "河口区",
                        id: 1548,
                        upid: 1545
                    },
                    {
                        name: "垦利县",
                        id: 1549,
                        upid: 1545
                    },
                    {
                        name: "利津县",
                        id: 1550,
                        upid: 1545
                    },
                    {
                        name: "广饶县",
                        id: 1551,
                        upid: 1545
                    }
                ]
            },
            {
                name: "烟台市",
                id: 1552,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1553,
                        upid: 1552
                    },
                    {
                        name: "芝罘区",
                        id: 1554,
                        upid: 1552
                    },
                    {
                        name: "福山区",
                        id: 1555,
                        upid: 1552
                    },
                    {
                        name: "牟平区",
                        id: 1556,
                        upid: 1552
                    },
                    {
                        name: "莱山区",
                        id: 1557,
                        upid: 1552
                    },
                    {
                        name: "长岛县",
                        id: 1558,
                        upid: 1552
                    },
                    {
                        name: "龙口市",
                        id: 1559,
                        upid: 1552
                    },
                    {
                        name: "莱阳市",
                        id: 1560,
                        upid: 1552
                    },
                    {
                        name: "莱州市",
                        id: 1561,
                        upid: 1552
                    },
                    {
                        name: "蓬莱市",
                        id: 1562,
                        upid: 1552
                    },
                    {
                        name: "招远市",
                        id: 1563,
                        upid: 1552
                    },
                    {
                        name: "栖霞市",
                        id: 1564,
                        upid: 1552
                    },
                    {
                        name: "海阳市",
                        id: 1565,
                        upid: 1552
                    }
                ]
            },
            {
                name: "潍坊市",
                id: 1566,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1567,
                        upid: 1566
                    },
                    {
                        name: "潍城区",
                        id: 1568,
                        upid: 1566
                    },
                    {
                        name: "寒亭区",
                        id: 1569,
                        upid: 1566
                    },
                    {
                        name: "坊子区",
                        id: 1570,
                        upid: 1566
                    },
                    {
                        name: "奎文区",
                        id: 1571,
                        upid: 1566
                    },
                    {
                        name: "临朐县",
                        id: 1572,
                        upid: 1566
                    },
                    {
                        name: "昌乐县",
                        id: 1573,
                        upid: 1566
                    },
                    {
                        name: "青州市",
                        id: 1574,
                        upid: 1566
                    },
                    {
                        name: "诸城市",
                        id: 1575,
                        upid: 1566
                    },
                    {
                        name: "寿光市",
                        id: 1576,
                        upid: 1566
                    },
                    {
                        name: "安丘市",
                        id: 1577,
                        upid: 1566
                    },
                    {
                        name: "高密市",
                        id: 1578,
                        upid: 1566
                    },
                    {
                        name: "昌邑市",
                        id: 1579,
                        upid: 1566
                    }
                ]
            },
            {
                name: "济宁市",
                id: 1580,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1581,
                        upid: 1580
                    },
                    {
                        name: "市中区",
                        id: 1582,
                        upid: 1580
                    },
                    {
                        name: "任城区",
                        id: 1583,
                        upid: 1580
                    },
                    {
                        name: "微山县",
                        id: 1584,
                        upid: 1580
                    },
                    {
                        name: "鱼台县",
                        id: 1585,
                        upid: 1580
                    },
                    {
                        name: "金乡县",
                        id: 1586,
                        upid: 1580
                    },
                    {
                        name: "嘉祥县",
                        id: 1587,
                        upid: 1580
                    },
                    {
                        name: "汶上县",
                        id: 1588,
                        upid: 1580
                    },
                    {
                        name: "泗水县",
                        id: 1589,
                        upid: 1580
                    },
                    {
                        name: "梁山县",
                        id: 1590,
                        upid: 1580
                    },
                    {
                        name: "曲阜市",
                        id: 1591,
                        upid: 1580
                    },
                    {
                        name: "兖州市",
                        id: 1592,
                        upid: 1580
                    },
                    {
                        name: "邹城市",
                        id: 1593,
                        upid: 1580
                    }
                ]
            },
            {
                name: "泰安市",
                id: 1594,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1595,
                        upid: 1594
                    },
                    {
                        name: "泰山区",
                        id: 1596,
                        upid: 1594
                    },
                    {
                        name: "岱岳区",
                        id: 1597,
                        upid: 1594
                    },
                    {
                        name: "宁阳县",
                        id: 1598,
                        upid: 1594
                    },
                    {
                        name: "东平县",
                        id: 1599,
                        upid: 1594
                    },
                    {
                        name: "新泰市",
                        id: 1600,
                        upid: 1594
                    },
                    {
                        name: "肥城市",
                        id: 1601,
                        upid: 1594
                    }
                ]
            },
            {
                name: "威海市",
                id: 1602,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1603,
                        upid: 1602
                    },
                    {
                        name: "环翠区",
                        id: 1604,
                        upid: 1602
                    },
                    {
                        name: "文登市",
                        id: 1605,
                        upid: 1602
                    },
                    {
                        name: "荣成市",
                        id: 1606,
                        upid: 1602
                    },
                    {
                        name: "乳山市",
                        id: 1607,
                        upid: 1602
                    }
                ]
            },
            {
                name: "日照市",
                id: 1608,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1609,
                        upid: 1608
                    },
                    {
                        name: "东港区",
                        id: 1610,
                        upid: 1608
                    },
                    {
                        name: "五莲县",
                        id: 1611,
                        upid: 1608
                    },
                    {
                        name: "莒县",
                        id: 1612,
                        upid: 1608
                    }
                ]
            },
            {
                name: "莱芜市",
                id: 1613,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1614,
                        upid: 1613
                    },
                    {
                        name: "莱城区",
                        id: 1615,
                        upid: 1613
                    },
                    {
                        name: "钢城区",
                        id: 1616,
                        upid: 1613
                    }
                ]
            },
            {
                name: "临沂市",
                id: 1617,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1618,
                        upid: 1617
                    },
                    {
                        name: "兰山区",
                        id: 1619,
                        upid: 1617
                    },
                    {
                        name: "罗庄区",
                        id: 1620,
                        upid: 1617
                    },
                    {
                        name: "河东区",
                        id: 1621,
                        upid: 1617
                    },
                    {
                        name: "沂南县",
                        id: 1622,
                        upid: 1617
                    },
                    {
                        name: "郯城县",
                        id: 1623,
                        upid: 1617
                    },
                    {
                        name: "沂水县",
                        id: 1624,
                        upid: 1617
                    },
                    {
                        name: "苍山县",
                        id: 1625,
                        upid: 1617
                    },
                    {
                        name: "费县",
                        id: 1626,
                        upid: 1617
                    },
                    {
                        name: "平邑县",
                        id: 1627,
                        upid: 1617
                    },
                    {
                        name: "莒南县",
                        id: 1628,
                        upid: 1617
                    },
                    {
                        name: "蒙阴县",
                        id: 1629,
                        upid: 1617
                    },
                    {
                        name: "临沭县",
                        id: 1630,
                        upid: 1617
                    }
                ]
            },
            {
                name: "德州市",
                id: 1631,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1632,
                        upid: 1631
                    },
                    {
                        name: "德城区",
                        id: 1633,
                        upid: 1631
                    },
                    {
                        name: "陵县",
                        id: 1634,
                        upid: 1631
                    },
                    {
                        name: "宁津县",
                        id: 1635,
                        upid: 1631
                    },
                    {
                        name: "庆云县",
                        id: 1636,
                        upid: 1631
                    },
                    {
                        name: "临邑县",
                        id: 1637,
                        upid: 1631
                    },
                    {
                        name: "齐河县",
                        id: 1638,
                        upid: 1631
                    },
                    {
                        name: "平原县",
                        id: 1639,
                        upid: 1631
                    },
                    {
                        name: "夏津县",
                        id: 1640,
                        upid: 1631
                    },
                    {
                        name: "武城县",
                        id: 1641,
                        upid: 1631
                    },
                    {
                        name: "乐陵市",
                        id: 1642,
                        upid: 1631
                    },
                    {
                        name: "禹城市",
                        id: 1643,
                        upid: 1631
                    }
                ]
            },
            {
                name: "聊城市",
                id: 1644,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1645,
                        upid: 1644
                    },
                    {
                        name: "东昌府区",
                        id: 1646,
                        upid: 1644
                    },
                    {
                        name: "阳谷县",
                        id: 1647,
                        upid: 1644
                    },
                    {
                        name: "莘县",
                        id: 1648,
                        upid: 1644
                    },
                    {
                        name: "茌平县",
                        id: 1649,
                        upid: 1644
                    },
                    {
                        name: "东阿县",
                        id: 1650,
                        upid: 1644
                    },
                    {
                        name: "冠县",
                        id: 1651,
                        upid: 1644
                    },
                    {
                        name: "高唐县",
                        id: 1652,
                        upid: 1644
                    },
                    {
                        name: "临清市",
                        id: 1653,
                        upid: 1644
                    }
                ]
            },
            {
                name: "滨州市",
                id: 1654,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1655,
                        upid: 1654
                    },
                    {
                        name: "滨城区",
                        id: 1656,
                        upid: 1654
                    },
                    {
                        name: "惠民县",
                        id: 1657,
                        upid: 1654
                    },
                    {
                        name: "阳信县",
                        id: 1658,
                        upid: 1654
                    },
                    {
                        name: "无棣县",
                        id: 1659,
                        upid: 1654
                    },
                    {
                        name: "沾化县",
                        id: 1660,
                        upid: 1654
                    },
                    {
                        name: "博兴县",
                        id: 1661,
                        upid: 1654
                    },
                    {
                        name: "邹平县",
                        id: 1662,
                        upid: 1654
                    }
                ]
            },
            {
                name: "菏泽市",
                id: 1663,
                upid: 1500,
                sub: [
                    {
                        name: "市辖区",
                        id: 1664,
                        upid: 1663
                    },
                    {
                        name: "牡丹区",
                        id: 1665,
                        upid: 1663
                    },
                    {
                        name: "曹县",
                        id: 1666,
                        upid: 1663
                    },
                    {
                        name: "单县",
                        id: 1667,
                        upid: 1663
                    },
                    {
                        name: "成武县",
                        id: 1668,
                        upid: 1663
                    },
                    {
                        name: "巨野县",
                        id: 1669,
                        upid: 1663
                    },
                    {
                        name: "郓城县",
                        id: 1670,
                        upid: 1663
                    },
                    {
                        name: "鄄城县",
                        id: 1671,
                        upid: 1663
                    },
                    {
                        name: "定陶县",
                        id: 1672,
                        upid: 1663
                    },
                    {
                        name: "东明县",
                        id: 1673,
                        upid: 1663
                    }
                ]
            }
        ]
    },
    {
        name: "河南",
        id: 1674,
        upid: 0,
        sub: [
            {
                name: "郑州市",
                id: 1675,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1676,
                        upid: 1675
                    },
                    {
                        name: "中原区",
                        id: 1677,
                        upid: 1675
                    },
                    {
                        name: "二七区",
                        id: 1678,
                        upid: 1675
                    },
                    {
                        name: "管城回族区",
                        id: 1679,
                        upid: 1675
                    },
                    {
                        name: "金水区",
                        id: 1680,
                        upid: 1675
                    },
                    {
                        name: "上街区",
                        id: 1681,
                        upid: 1675
                    },
                    {
                        name: "邙山区",
                        id: 1682,
                        upid: 1675
                    },
                    {
                        name: "中牟县",
                        id: 1683,
                        upid: 1675
                    },
                    {
                        name: "巩义市",
                        id: 1684,
                        upid: 1675
                    },
                    {
                        name: "荥阳市",
                        id: 1685,
                        upid: 1675
                    },
                    {
                        name: "新密市",
                        id: 1686,
                        upid: 1675
                    },
                    {
                        name: "新郑市",
                        id: 1687,
                        upid: 1675
                    },
                    {
                        name: "登封市",
                        id: 1688,
                        upid: 1675
                    }
                ]
            },
            {
                name: "开封市",
                id: 1689,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1690,
                        upid: 1689
                    },
                    {
                        name: "龙亭区",
                        id: 1691,
                        upid: 1689
                    },
                    {
                        name: "顺河回族区",
                        id: 1692,
                        upid: 1689
                    },
                    {
                        name: "鼓楼区",
                        id: 1693,
                        upid: 1689
                    },
                    {
                        name: "南关区",
                        id: 1694,
                        upid: 1689
                    },
                    {
                        name: "郊区",
                        id: 1695,
                        upid: 1689
                    },
                    {
                        name: "杞县",
                        id: 1696,
                        upid: 1689
                    },
                    {
                        name: "通许县",
                        id: 1697,
                        upid: 1689
                    },
                    {
                        name: "尉氏县",
                        id: 1698,
                        upid: 1689
                    },
                    {
                        name: "开封县",
                        id: 1699,
                        upid: 1689
                    },
                    {
                        name: "兰考县",
                        id: 1700,
                        upid: 1689
                    }
                ]
            },
            {
                name: "洛阳市",
                id: 1701,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1702,
                        upid: 1701
                    },
                    {
                        name: "老城区",
                        id: 1703,
                        upid: 1701
                    },
                    {
                        name: "西工区",
                        id: 1704,
                        upid: 1701
                    },
                    {
                        name: "廛河回族区",
                        id: 1705,
                        upid: 1701
                    },
                    {
                        name: "涧西区",
                        id: 1706,
                        upid: 1701
                    },
                    {
                        name: "吉利区",
                        id: 1707,
                        upid: 1701
                    },
                    {
                        name: "洛龙区",
                        id: 1708,
                        upid: 1701
                    },
                    {
                        name: "孟津县",
                        id: 1709,
                        upid: 1701
                    },
                    {
                        name: "新安县",
                        id: 1710,
                        upid: 1701
                    },
                    {
                        name: "栾川县",
                        id: 1711,
                        upid: 1701
                    },
                    {
                        name: "嵩县",
                        id: 1712,
                        upid: 1701
                    },
                    {
                        name: "汝阳县",
                        id: 1713,
                        upid: 1701
                    },
                    {
                        name: "宜阳县",
                        id: 1714,
                        upid: 1701
                    },
                    {
                        name: "洛宁县",
                        id: 1715,
                        upid: 1701
                    },
                    {
                        name: "伊川县",
                        id: 1716,
                        upid: 1701
                    },
                    {
                        name: "偃师市",
                        id: 1717,
                        upid: 1701
                    }
                ]
            },
            {
                name: "平顶山市",
                id: 1718,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1719,
                        upid: 1718
                    },
                    {
                        name: "新华区",
                        id: 1720,
                        upid: 1718
                    },
                    {
                        name: "卫东区",
                        id: 1721,
                        upid: 1718
                    },
                    {
                        name: "石龙区",
                        id: 1722,
                        upid: 1718
                    },
                    {
                        name: "湛河区",
                        id: 1723,
                        upid: 1718
                    },
                    {
                        name: "宝丰县",
                        id: 1724,
                        upid: 1718
                    },
                    {
                        name: "叶县",
                        id: 1725,
                        upid: 1718
                    },
                    {
                        name: "鲁山县",
                        id: 1726,
                        upid: 1718
                    },
                    {
                        name: "郏县",
                        id: 1727,
                        upid: 1718
                    },
                    {
                        name: "舞钢市",
                        id: 1728,
                        upid: 1718
                    },
                    {
                        name: "汝州市",
                        id: 1729,
                        upid: 1718
                    }
                ]
            },
            {
                name: "安阳市",
                id: 1730,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1731,
                        upid: 1730
                    },
                    {
                        name: "文峰区",
                        id: 1732,
                        upid: 1730
                    },
                    {
                        name: "北关区",
                        id: 1733,
                        upid: 1730
                    },
                    {
                        name: "殷都区",
                        id: 1734,
                        upid: 1730
                    },
                    {
                        name: "龙安区",
                        id: 1735,
                        upid: 1730
                    },
                    {
                        name: "安阳县",
                        id: 1736,
                        upid: 1730
                    },
                    {
                        name: "汤阴县",
                        id: 1737,
                        upid: 1730
                    },
                    {
                        name: "滑县",
                        id: 1738,
                        upid: 1730
                    },
                    {
                        name: "内黄县",
                        id: 1739,
                        upid: 1730
                    },
                    {
                        name: "林州市",
                        id: 1740,
                        upid: 1730
                    }
                ]
            },
            {
                name: "鹤壁市",
                id: 1741,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1742,
                        upid: 1741
                    },
                    {
                        name: "鹤山区",
                        id: 1743,
                        upid: 1741
                    },
                    {
                        name: "山城区",
                        id: 1744,
                        upid: 1741
                    },
                    {
                        name: "淇滨区",
                        id: 1745,
                        upid: 1741
                    },
                    {
                        name: "浚县",
                        id: 1746,
                        upid: 1741
                    },
                    {
                        name: "淇县",
                        id: 1747,
                        upid: 1741
                    }
                ]
            },
            {
                name: "新乡市",
                id: 1748,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1749,
                        upid: 1748
                    },
                    {
                        name: "红旗区",
                        id: 1750,
                        upid: 1748
                    },
                    {
                        name: "卫滨区",
                        id: 1751,
                        upid: 1748
                    },
                    {
                        name: "凤泉区",
                        id: 1752,
                        upid: 1748
                    },
                    {
                        name: "牧野区",
                        id: 1753,
                        upid: 1748
                    },
                    {
                        name: "新乡县",
                        id: 1754,
                        upid: 1748
                    },
                    {
                        name: "获嘉县",
                        id: 1755,
                        upid: 1748
                    },
                    {
                        name: "原阳县",
                        id: 1756,
                        upid: 1748
                    },
                    {
                        name: "延津县",
                        id: 1757,
                        upid: 1748
                    },
                    {
                        name: "封丘县",
                        id: 1758,
                        upid: 1748
                    },
                    {
                        name: "长垣县",
                        id: 1759,
                        upid: 1748
                    },
                    {
                        name: "卫辉市",
                        id: 1760,
                        upid: 1748
                    },
                    {
                        name: "辉县市",
                        id: 1761,
                        upid: 1748
                    }
                ]
            },
            {
                name: "焦作市",
                id: 1762,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1763,
                        upid: 1762
                    },
                    {
                        name: "解放区",
                        id: 1764,
                        upid: 1762
                    },
                    {
                        name: "中站区",
                        id: 1765,
                        upid: 1762
                    },
                    {
                        name: "马村区",
                        id: 1766,
                        upid: 1762
                    },
                    {
                        name: "山阳区",
                        id: 1767,
                        upid: 1762
                    },
                    {
                        name: "修武县",
                        id: 1768,
                        upid: 1762
                    },
                    {
                        name: "博爱县",
                        id: 1769,
                        upid: 1762
                    },
                    {
                        name: "武陟县",
                        id: 1770,
                        upid: 1762
                    },
                    {
                        name: "温县",
                        id: 1771,
                        upid: 1762
                    },
                    {
                        name: "济源市",
                        id: 1772,
                        upid: 1762
                    },
                    {
                        name: "沁阳市",
                        id: 1773,
                        upid: 1762
                    },
                    {
                        name: "孟州市",
                        id: 1774,
                        upid: 1762
                    }
                ]
            },
            {
                name: "濮阳市",
                id: 1775,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1776,
                        upid: 1775
                    },
                    {
                        name: "华龙区",
                        id: 1777,
                        upid: 1775
                    },
                    {
                        name: "清丰县",
                        id: 1778,
                        upid: 1775
                    },
                    {
                        name: "南乐县",
                        id: 1779,
                        upid: 1775
                    },
                    {
                        name: "范县",
                        id: 1780,
                        upid: 1775
                    },
                    {
                        name: "台前县",
                        id: 1781,
                        upid: 1775
                    },
                    {
                        name: "濮阳县",
                        id: 1782,
                        upid: 1775
                    }
                ]
            },
            {
                name: "许昌市",
                id: 1783,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1784,
                        upid: 1783
                    },
                    {
                        name: "魏都区",
                        id: 1785,
                        upid: 1783
                    },
                    {
                        name: "许昌县",
                        id: 1786,
                        upid: 1783
                    },
                    {
                        name: "鄢陵县",
                        id: 1787,
                        upid: 1783
                    },
                    {
                        name: "襄城县",
                        id: 1788,
                        upid: 1783
                    },
                    {
                        name: "禹州市",
                        id: 1789,
                        upid: 1783
                    },
                    {
                        name: "长葛市",
                        id: 1790,
                        upid: 1783
                    }
                ]
            },
            {
                name: "漯河市",
                id: 1791,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1792,
                        upid: 1791
                    },
                    {
                        name: "源汇区",
                        id: 1793,
                        upid: 1791
                    },
                    {
                        name: "舞阳县",
                        id: 1794,
                        upid: 1791
                    },
                    {
                        name: "临颍县",
                        id: 1795,
                        upid: 1791
                    },
                    {
                        name: "郾城县",
                        id: 1796,
                        upid: 1791
                    }
                ]
            },
            {
                name: "三门峡市",
                id: 1797,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1798,
                        upid: 1797
                    },
                    {
                        name: "湖滨区",
                        id: 1799,
                        upid: 1797
                    },
                    {
                        name: "渑池县",
                        id: 1800,
                        upid: 1797
                    },
                    {
                        name: "陕县",
                        id: 1801,
                        upid: 1797
                    },
                    {
                        name: "卢氏县",
                        id: 1802,
                        upid: 1797
                    },
                    {
                        name: "义马市",
                        id: 1803,
                        upid: 1797
                    },
                    {
                        name: "灵宝市",
                        id: 1804,
                        upid: 1797
                    }
                ]
            },
            {
                name: "南阳市",
                id: 1805,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1806,
                        upid: 1805
                    },
                    {
                        name: "宛城区",
                        id: 1807,
                        upid: 1805
                    },
                    {
                        name: "卧龙区",
                        id: 1808,
                        upid: 1805
                    },
                    {
                        name: "南召县",
                        id: 1809,
                        upid: 1805
                    },
                    {
                        name: "方城县",
                        id: 1810,
                        upid: 1805
                    },
                    {
                        name: "西峡县",
                        id: 1811,
                        upid: 1805
                    },
                    {
                        name: "镇平县",
                        id: 1812,
                        upid: 1805
                    },
                    {
                        name: "内乡县",
                        id: 1813,
                        upid: 1805
                    },
                    {
                        name: "淅川县",
                        id: 1814,
                        upid: 1805
                    },
                    {
                        name: "社旗县",
                        id: 1815,
                        upid: 1805
                    },
                    {
                        name: "唐河县",
                        id: 1816,
                        upid: 1805
                    },
                    {
                        name: "新野县",
                        id: 1817,
                        upid: 1805
                    },
                    {
                        name: "桐柏县",
                        id: 1818,
                        upid: 1805
                    },
                    {
                        name: "邓州市",
                        id: 1819,
                        upid: 1805
                    }
                ]
            },
            {
                name: "商丘市",
                id: 1820,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1821,
                        upid: 1820
                    },
                    {
                        name: "梁园区",
                        id: 1822,
                        upid: 1820
                    },
                    {
                        name: "睢阳区",
                        id: 1823,
                        upid: 1820
                    },
                    {
                        name: "民权县",
                        id: 1824,
                        upid: 1820
                    },
                    {
                        name: "睢县",
                        id: 1825,
                        upid: 1820
                    },
                    {
                        name: "宁陵县",
                        id: 1826,
                        upid: 1820
                    },
                    {
                        name: "柘城县",
                        id: 1827,
                        upid: 1820
                    },
                    {
                        name: "虞城县",
                        id: 1828,
                        upid: 1820
                    },
                    {
                        name: "夏邑县",
                        id: 1829,
                        upid: 1820
                    },
                    {
                        name: "永城市",
                        id: 1830,
                        upid: 1820
                    }
                ]
            },
            {
                name: "信阳市",
                id: 1831,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1832,
                        upid: 1831
                    },
                    {
                        name: "师河区",
                        id: 1833,
                        upid: 1831
                    },
                    {
                        name: "平桥区",
                        id: 1834,
                        upid: 1831
                    },
                    {
                        name: "罗山县",
                        id: 1835,
                        upid: 1831
                    },
                    {
                        name: "光山县",
                        id: 1836,
                        upid: 1831
                    },
                    {
                        name: "新县",
                        id: 1837,
                        upid: 1831
                    },
                    {
                        name: "商城县",
                        id: 1838,
                        upid: 1831
                    },
                    {
                        name: "固始县",
                        id: 1839,
                        upid: 1831
                    },
                    {
                        name: "潢川县",
                        id: 1840,
                        upid: 1831
                    },
                    {
                        name: "淮滨县",
                        id: 1841,
                        upid: 1831
                    },
                    {
                        name: "息县",
                        id: 1842,
                        upid: 1831
                    }
                ]
            },
            {
                name: "周口市",
                id: 1843,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1844,
                        upid: 1843
                    },
                    {
                        name: "川汇区",
                        id: 1845,
                        upid: 1843
                    },
                    {
                        name: "扶沟县",
                        id: 1846,
                        upid: 1843
                    },
                    {
                        name: "西华县",
                        id: 1847,
                        upid: 1843
                    },
                    {
                        name: "商水县",
                        id: 1848,
                        upid: 1843
                    },
                    {
                        name: "沈丘县",
                        id: 1849,
                        upid: 1843
                    },
                    {
                        name: "郸城县",
                        id: 1850,
                        upid: 1843
                    },
                    {
                        name: "淮阳县",
                        id: 1851,
                        upid: 1843
                    },
                    {
                        name: "太康县",
                        id: 1852,
                        upid: 1843
                    },
                    {
                        name: "鹿邑县",
                        id: 1853,
                        upid: 1843
                    },
                    {
                        name: "项城市",
                        id: 1854,
                        upid: 1843
                    }
                ]
            },
            {
                name: "驻马店市",
                id: 1855,
                upid: 1674,
                sub: [
                    {
                        name: "市辖区",
                        id: 1856,
                        upid: 1855
                    },
                    {
                        name: "驿城区",
                        id: 1857,
                        upid: 1855
                    },
                    {
                        name: "西平县",
                        id: 1858,
                        upid: 1855
                    },
                    {
                        name: "上蔡县",
                        id: 1859,
                        upid: 1855
                    },
                    {
                        name: "平舆县",
                        id: 1860,
                        upid: 1855
                    },
                    {
                        name: "正阳县",
                        id: 1861,
                        upid: 1855
                    },
                    {
                        name: "确山县",
                        id: 1862,
                        upid: 1855
                    },
                    {
                        name: "泌阳县",
                        id: 1863,
                        upid: 1855
                    },
                    {
                        name: "汝南县",
                        id: 1864,
                        upid: 1855
                    },
                    {
                        name: "遂平县",
                        id: 1865,
                        upid: 1855
                    },
                    {
                        name: "新蔡县",
                        id: 1866,
                        upid: 1855
                    }
                ]
            }
        ]
    },
    {
        name: "湖北",
        id: 1867,
        upid: 0,
        sub: [
            {
                name: "武汉市",
                id: 1868,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1869,
                        upid: 1868
                    },
                    {
                        name: "江岸区",
                        id: 1870,
                        upid: 1868
                    },
                    {
                        name: "江汉区",
                        id: 1871,
                        upid: 1868
                    },
                    {
                        name: "乔口区",
                        id: 1872,
                        upid: 1868
                    },
                    {
                        name: "汉阳区",
                        id: 1873,
                        upid: 1868
                    },
                    {
                        name: "武昌区",
                        id: 1874,
                        upid: 1868
                    },
                    {
                        name: "青山区",
                        id: 1875,
                        upid: 1868
                    },
                    {
                        name: "洪山区",
                        id: 1876,
                        upid: 1868
                    },
                    {
                        name: "东西湖区",
                        id: 1877,
                        upid: 1868
                    },
                    {
                        name: "汉南区",
                        id: 1878,
                        upid: 1868
                    },
                    {
                        name: "蔡甸区",
                        id: 1879,
                        upid: 1868
                    },
                    {
                        name: "江夏区",
                        id: 1880,
                        upid: 1868
                    },
                    {
                        name: "黄陂区",
                        id: 1881,
                        upid: 1868
                    },
                    {
                        name: "新洲区",
                        id: 1882,
                        upid: 1868
                    }
                ]
            },
            {
                name: "黄石市",
                id: 1883,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1884,
                        upid: 1883
                    },
                    {
                        name: "黄石港区",
                        id: 1885,
                        upid: 1883
                    },
                    {
                        name: "西塞山区",
                        id: 1886,
                        upid: 1883
                    },
                    {
                        name: "下陆区",
                        id: 1887,
                        upid: 1883
                    },
                    {
                        name: "铁山区",
                        id: 1888,
                        upid: 1883
                    },
                    {
                        name: "阳新县",
                        id: 1889,
                        upid: 1883
                    },
                    {
                        name: "大冶市",
                        id: 1890,
                        upid: 1883
                    }
                ]
            },
            {
                name: "十堰市",
                id: 1891,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1892,
                        upid: 1891
                    },
                    {
                        name: "茅箭区",
                        id: 1893,
                        upid: 1891
                    },
                    {
                        name: "张湾区",
                        id: 1894,
                        upid: 1891
                    },
                    {
                        name: "郧县",
                        id: 1895,
                        upid: 1891
                    },
                    {
                        name: "郧西县",
                        id: 1896,
                        upid: 1891
                    },
                    {
                        name: "竹山县",
                        id: 1897,
                        upid: 1891
                    },
                    {
                        name: "竹溪县",
                        id: 1898,
                        upid: 1891
                    },
                    {
                        name: "房县",
                        id: 1899,
                        upid: 1891
                    },
                    {
                        name: "丹江口市",
                        id: 1900,
                        upid: 1891
                    }
                ]
            },
            {
                name: "宜昌市",
                id: 1901,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1902,
                        upid: 1901
                    },
                    {
                        name: "西陵区",
                        id: 1903,
                        upid: 1901
                    },
                    {
                        name: "伍家岗区",
                        id: 1904,
                        upid: 1901
                    },
                    {
                        name: "点军区",
                        id: 1905,
                        upid: 1901
                    },
                    {
                        name: "虎亭区",
                        id: 1906,
                        upid: 1901
                    },
                    {
                        name: "夷陵区",
                        id: 1907,
                        upid: 1901
                    },
                    {
                        name: "远安县",
                        id: 1908,
                        upid: 1901
                    },
                    {
                        name: "兴山县",
                        id: 1909,
                        upid: 1901
                    },
                    {
                        name: "秭归县",
                        id: 1910,
                        upid: 1901
                    },
                    {
                        name: "长阳土家族自治县",
                        id: 1911,
                        upid: 1901
                    },
                    {
                        name: "五峰土家族自治县",
                        id: 1912,
                        upid: 1901
                    },
                    {
                        name: "宜都市",
                        id: 1913,
                        upid: 1901
                    },
                    {
                        name: "当阳市",
                        id: 1914,
                        upid: 1901
                    },
                    {
                        name: "枝江市",
                        id: 1915,
                        upid: 1901
                    }
                ]
            },
            {
                name: "襄樊市",
                id: 1916,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1917,
                        upid: 1916
                    },
                    {
                        name: "襄城区",
                        id: 1918,
                        upid: 1916
                    },
                    {
                        name: "樊城区",
                        id: 1919,
                        upid: 1916
                    },
                    {
                        name: "襄阳区",
                        id: 1920,
                        upid: 1916
                    },
                    {
                        name: "南漳县",
                        id: 1921,
                        upid: 1916
                    },
                    {
                        name: "谷城县",
                        id: 1922,
                        upid: 1916
                    },
                    {
                        name: "保康县",
                        id: 1923,
                        upid: 1916
                    },
                    {
                        name: "老河口市",
                        id: 1924,
                        upid: 1916
                    },
                    {
                        name: "枣阳市",
                        id: 1925,
                        upid: 1916
                    },
                    {
                        name: "宜城市",
                        id: 1926,
                        upid: 1916
                    }
                ]
            },
            {
                name: "鄂州市",
                id: 1927,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1928,
                        upid: 1927
                    },
                    {
                        name: "梁子湖区",
                        id: 1929,
                        upid: 1927
                    },
                    {
                        name: "华容区",
                        id: 1930,
                        upid: 1927
                    },
                    {
                        name: "鄂城区",
                        id: 1931,
                        upid: 1927
                    }
                ]
            },
            {
                name: "荆门市",
                id: 1932,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1933,
                        upid: 1932
                    },
                    {
                        name: "东宝区",
                        id: 1934,
                        upid: 1932
                    },
                    {
                        name: "掇刀区",
                        id: 1935,
                        upid: 1932
                    },
                    {
                        name: "京山县",
                        id: 1936,
                        upid: 1932
                    },
                    {
                        name: "沙洋县",
                        id: 1937,
                        upid: 1932
                    },
                    {
                        name: "钟祥市",
                        id: 1938,
                        upid: 1932
                    }
                ]
            },
            {
                name: "孝感市",
                id: 1939,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1940,
                        upid: 1939
                    },
                    {
                        name: "孝南区",
                        id: 1941,
                        upid: 1939
                    },
                    {
                        name: "孝昌县",
                        id: 1942,
                        upid: 1939
                    },
                    {
                        name: "大悟县",
                        id: 1943,
                        upid: 1939
                    },
                    {
                        name: "云梦县",
                        id: 1944,
                        upid: 1939
                    },
                    {
                        name: "应城市",
                        id: 1945,
                        upid: 1939
                    },
                    {
                        name: "安陆市",
                        id: 1946,
                        upid: 1939
                    },
                    {
                        name: "汉川市",
                        id: 1947,
                        upid: 1939
                    }
                ]
            },
            {
                name: "荆州市",
                id: 1948,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1949,
                        upid: 1948
                    },
                    {
                        name: "沙市区",
                        id: 1950,
                        upid: 1948
                    },
                    {
                        name: "荆州区",
                        id: 1951,
                        upid: 1948
                    },
                    {
                        name: "公安县",
                        id: 1952,
                        upid: 1948
                    },
                    {
                        name: "监利县",
                        id: 1953,
                        upid: 1948
                    },
                    {
                        name: "江陵县",
                        id: 1954,
                        upid: 1948
                    },
                    {
                        name: "石首市",
                        id: 1955,
                        upid: 1948
                    },
                    {
                        name: "洪湖市",
                        id: 1956,
                        upid: 1948
                    },
                    {
                        name: "松滋市",
                        id: 1957,
                        upid: 1948
                    }
                ]
            },
            {
                name: "黄冈市",
                id: 1958,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1959,
                        upid: 1958
                    },
                    {
                        name: "黄州区",
                        id: 1960,
                        upid: 1958
                    },
                    {
                        name: "团风县",
                        id: 1961,
                        upid: 1958
                    },
                    {
                        name: "红安县",
                        id: 1962,
                        upid: 1958
                    },
                    {
                        name: "罗田县",
                        id: 1963,
                        upid: 1958
                    },
                    {
                        name: "英山县",
                        id: 1964,
                        upid: 1958
                    },
                    {
                        name: "浠水县",
                        id: 1965,
                        upid: 1958
                    },
                    {
                        name: "蕲春县",
                        id: 1966,
                        upid: 1958
                    },
                    {
                        name: "黄梅县",
                        id: 1967,
                        upid: 1958
                    },
                    {
                        name: "麻城市",
                        id: 1968,
                        upid: 1958
                    },
                    {
                        name: "武穴市",
                        id: 1969,
                        upid: 1958
                    }
                ]
            },
            {
                name: "咸宁市",
                id: 1970,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1971,
                        upid: 1970
                    },
                    {
                        name: "咸安区",
                        id: 1972,
                        upid: 1970
                    },
                    {
                        name: "嘉鱼县",
                        id: 1973,
                        upid: 1970
                    },
                    {
                        name: "通城县",
                        id: 1974,
                        upid: 1970
                    },
                    {
                        name: "崇阳县",
                        id: 1975,
                        upid: 1970
                    },
                    {
                        name: "通山县",
                        id: 1976,
                        upid: 1970
                    },
                    {
                        name: "赤壁市",
                        id: 1977,
                        upid: 1970
                    }
                ]
            },
            {
                name: "随州市",
                id: 1978,
                upid: 1867,
                sub: [
                    {
                        name: "市辖区",
                        id: 1979,
                        upid: 1978
                    },
                    {
                        name: "曾都区",
                        id: 1980,
                        upid: 1978
                    },
                    {
                        name: "广水市",
                        id: 1981,
                        upid: 1978
                    }
                ]
            },
            {
                name: "恩施土家族苗族自治州",
                id: 1982,
                upid: 1867,
                sub: [
                    {
                        name: "恩施市",
                        id: 1983,
                        upid: 1982
                    },
                    {
                        name: "利川市",
                        id: 1984,
                        upid: 1982
                    },
                    {
                        name: "建始县",
                        id: 1985,
                        upid: 1982
                    },
                    {
                        name: "巴东县",
                        id: 1986,
                        upid: 1982
                    },
                    {
                        name: "宣恩县",
                        id: 1987,
                        upid: 1982
                    },
                    {
                        name: "咸丰县",
                        id: 1988,
                        upid: 1982
                    },
                    {
                        name: "来凤县",
                        id: 1989,
                        upid: 1982
                    },
                    {
                        name: "鹤峰县",
                        id: 1990,
                        upid: 1982
                    }
                ]
            },
            {
                name: "省直辖行政单位",
                id: 1991,
                upid: 1867,
                sub: [
                    {
                        name: "仙桃市",
                        id: 1992,
                        upid: 1991
                    },
                    {
                        name: "潜江市",
                        id: 1993,
                        upid: 1991
                    },
                    {
                        name: "天门市",
                        id: 1994,
                        upid: 1991
                    },
                    {
                        name: "神农架林区",
                        id: 1995,
                        upid: 1991
                    }
                ]
            }
        ]
    },
    {
        name: "湖南",
        id: 1996,
        upid: 0,
        sub: [
            {
                name: "长沙市",
                id: 1997,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 1998,
                        upid: 1997
                    },
                    {
                        name: "芙蓉区",
                        id: 1999,
                        upid: 1997
                    },
                    {
                        name: "天心区",
                        id: 2000,
                        upid: 1997
                    },
                    {
                        name: "岳麓区",
                        id: 2001,
                        upid: 1997
                    },
                    {
                        name: "开福区",
                        id: 2002,
                        upid: 1997
                    },
                    {
                        name: "雨花区",
                        id: 2003,
                        upid: 1997
                    },
                    {
                        name: "长沙县",
                        id: 2004,
                        upid: 1997
                    },
                    {
                        name: "望城县",
                        id: 2005,
                        upid: 1997
                    },
                    {
                        name: "宁乡县",
                        id: 2006,
                        upid: 1997
                    },
                    {
                        name: "浏阳市",
                        id: 2007,
                        upid: 1997
                    }
                ]
            },
            {
                name: "株洲市",
                id: 2008,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2009,
                        upid: 2008
                    },
                    {
                        name: "荷塘区",
                        id: 2010,
                        upid: 2008
                    },
                    {
                        name: "芦淞区",
                        id: 2011,
                        upid: 2008
                    },
                    {
                        name: "石峰区",
                        id: 2012,
                        upid: 2008
                    },
                    {
                        name: "天元区",
                        id: 2013,
                        upid: 2008
                    },
                    {
                        name: "株洲县",
                        id: 2014,
                        upid: 2008
                    },
                    {
                        name: "攸县",
                        id: 2015,
                        upid: 2008
                    },
                    {
                        name: "茶陵县",
                        id: 2016,
                        upid: 2008
                    },
                    {
                        name: "炎陵县",
                        id: 2017,
                        upid: 2008
                    },
                    {
                        name: "醴陵市",
                        id: 2018,
                        upid: 2008
                    }
                ]
            },
            {
                name: "湘潭市",
                id: 2019,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2020,
                        upid: 2019
                    },
                    {
                        name: "雨湖区",
                        id: 2021,
                        upid: 2019
                    },
                    {
                        name: "岳塘区",
                        id: 2022,
                        upid: 2019
                    },
                    {
                        name: "湘潭县",
                        id: 2023,
                        upid: 2019
                    },
                    {
                        name: "湘乡市",
                        id: 2024,
                        upid: 2019
                    },
                    {
                        name: "韶山市",
                        id: 2025,
                        upid: 2019
                    }
                ]
            },
            {
                name: "衡阳市",
                id: 2026,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2027,
                        upid: 2026
                    },
                    {
                        name: "珠晖区",
                        id: 2028,
                        upid: 2026
                    },
                    {
                        name: "雁峰区",
                        id: 2029,
                        upid: 2026
                    },
                    {
                        name: "石鼓区",
                        id: 2030,
                        upid: 2026
                    },
                    {
                        name: "蒸湘区",
                        id: 2031,
                        upid: 2026
                    },
                    {
                        name: "南岳区",
                        id: 2032,
                        upid: 2026
                    },
                    {
                        name: "衡阳县",
                        id: 2033,
                        upid: 2026
                    },
                    {
                        name: "衡南县",
                        id: 2034,
                        upid: 2026
                    },
                    {
                        name: "衡山县",
                        id: 2035,
                        upid: 2026
                    },
                    {
                        name: "衡东县",
                        id: 2036,
                        upid: 2026
                    },
                    {
                        name: "祁东县",
                        id: 2037,
                        upid: 2026
                    },
                    {
                        name: "耒阳市",
                        id: 2038,
                        upid: 2026
                    },
                    {
                        name: "常宁市",
                        id: 2039,
                        upid: 2026
                    }
                ]
            },
            {
                name: "邵阳市",
                id: 2040,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2041,
                        upid: 2040
                    },
                    {
                        name: "双清区",
                        id: 2042,
                        upid: 2040
                    },
                    {
                        name: "大祥区",
                        id: 2043,
                        upid: 2040
                    },
                    {
                        name: "北塔区",
                        id: 2044,
                        upid: 2040
                    },
                    {
                        name: "邵东县",
                        id: 2045,
                        upid: 2040
                    },
                    {
                        name: "新邵县",
                        id: 2046,
                        upid: 2040
                    },
                    {
                        name: "邵阳县",
                        id: 2047,
                        upid: 2040
                    },
                    {
                        name: "隆回县",
                        id: 2048,
                        upid: 2040
                    },
                    {
                        name: "洞口县",
                        id: 2049,
                        upid: 2040
                    },
                    {
                        name: "绥宁县",
                        id: 2050,
                        upid: 2040
                    },
                    {
                        name: "新宁县",
                        id: 2051,
                        upid: 2040
                    },
                    {
                        name: "城步苗族自治县",
                        id: 2052,
                        upid: 2040
                    },
                    {
                        name: "武冈市",
                        id: 2053,
                        upid: 2040
                    }
                ]
            },
            {
                name: "岳阳市",
                id: 2054,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2055,
                        upid: 2054
                    },
                    {
                        name: "岳阳楼区",
                        id: 2056,
                        upid: 2054
                    },
                    {
                        name: "云溪区",
                        id: 2057,
                        upid: 2054
                    },
                    {
                        name: "君山区",
                        id: 2058,
                        upid: 2054
                    },
                    {
                        name: "岳阳县",
                        id: 2059,
                        upid: 2054
                    },
                    {
                        name: "华容县",
                        id: 2060,
                        upid: 2054
                    },
                    {
                        name: "湘阴县",
                        id: 2061,
                        upid: 2054
                    },
                    {
                        name: "平江县",
                        id: 2062,
                        upid: 2054
                    },
                    {
                        name: "汨罗市",
                        id: 2063,
                        upid: 2054
                    },
                    {
                        name: "临湘市",
                        id: 2064,
                        upid: 2054
                    }
                ]
            },
            {
                name: "常德市",
                id: 2065,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2066,
                        upid: 2065
                    },
                    {
                        name: "武陵区",
                        id: 2067,
                        upid: 2065
                    },
                    {
                        name: "鼎城区",
                        id: 2068,
                        upid: 2065
                    },
                    {
                        name: "安乡县",
                        id: 2069,
                        upid: 2065
                    },
                    {
                        name: "汉寿县",
                        id: 2070,
                        upid: 2065
                    },
                    {
                        name: "澧县",
                        id: 2071,
                        upid: 2065
                    },
                    {
                        name: "临澧县",
                        id: 2072,
                        upid: 2065
                    },
                    {
                        name: "桃源县",
                        id: 2073,
                        upid: 2065
                    },
                    {
                        name: "石门县",
                        id: 2074,
                        upid: 2065
                    },
                    {
                        name: "津市市",
                        id: 2075,
                        upid: 2065
                    }
                ]
            },
            {
                name: "张家界市",
                id: 2076,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2077,
                        upid: 2076
                    },
                    {
                        name: "永定区",
                        id: 2078,
                        upid: 2076
                    },
                    {
                        name: "武陵源区",
                        id: 2079,
                        upid: 2076
                    },
                    {
                        name: "慈利县",
                        id: 2080,
                        upid: 2076
                    },
                    {
                        name: "桑植县",
                        id: 2081,
                        upid: 2076
                    }
                ]
            },
            {
                name: "益阳市",
                id: 2082,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2083,
                        upid: 2082
                    },
                    {
                        name: "资阳区",
                        id: 2084,
                        upid: 2082
                    },
                    {
                        name: "赫山区",
                        id: 2085,
                        upid: 2082
                    },
                    {
                        name: "南县",
                        id: 2086,
                        upid: 2082
                    },
                    {
                        name: "桃江县",
                        id: 2087,
                        upid: 2082
                    },
                    {
                        name: "安化县",
                        id: 2088,
                        upid: 2082
                    },
                    {
                        name: "沅江市",
                        id: 2089,
                        upid: 2082
                    }
                ]
            },
            {
                name: "郴州市",
                id: 2090,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2091,
                        upid: 2090
                    },
                    {
                        name: "北湖区",
                        id: 2092,
                        upid: 2090
                    },
                    {
                        name: "苏仙区",
                        id: 2093,
                        upid: 2090
                    },
                    {
                        name: "桂阳县",
                        id: 2094,
                        upid: 2090
                    },
                    {
                        name: "宜章县",
                        id: 2095,
                        upid: 2090
                    },
                    {
                        name: "永兴县",
                        id: 2096,
                        upid: 2090
                    },
                    {
                        name: "嘉禾县",
                        id: 2097,
                        upid: 2090
                    },
                    {
                        name: "临武县",
                        id: 2098,
                        upid: 2090
                    },
                    {
                        name: "汝城县",
                        id: 2099,
                        upid: 2090
                    },
                    {
                        name: "桂东县",
                        id: 2100,
                        upid: 2090
                    },
                    {
                        name: "安仁县",
                        id: 2101,
                        upid: 2090
                    },
                    {
                        name: "资兴市",
                        id: 2102,
                        upid: 2090
                    }
                ]
            },
            {
                name: "永州市",
                id: 2103,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2104,
                        upid: 2103
                    },
                    {
                        name: "芝山区",
                        id: 2105,
                        upid: 2103
                    },
                    {
                        name: "冷水滩区",
                        id: 2106,
                        upid: 2103
                    },
                    {
                        name: "祁阳县",
                        id: 2107,
                        upid: 2103
                    },
                    {
                        name: "东安县",
                        id: 2108,
                        upid: 2103
                    },
                    {
                        name: "双牌县",
                        id: 2109,
                        upid: 2103
                    },
                    {
                        name: "道县",
                        id: 2110,
                        upid: 2103
                    },
                    {
                        name: "江永县",
                        id: 2111,
                        upid: 2103
                    },
                    {
                        name: "宁远县",
                        id: 2112,
                        upid: 2103
                    },
                    {
                        name: "蓝山县",
                        id: 2113,
                        upid: 2103
                    },
                    {
                        name: "新田县",
                        id: 2114,
                        upid: 2103
                    },
                    {
                        name: "江华瑶族自治县",
                        id: 2115,
                        upid: 2103
                    }
                ]
            },
            {
                name: "怀化市",
                id: 2116,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2117,
                        upid: 2116
                    },
                    {
                        name: "鹤城区",
                        id: 2118,
                        upid: 2116
                    },
                    {
                        name: "中方县",
                        id: 2119,
                        upid: 2116
                    },
                    {
                        name: "沅陵县",
                        id: 2120,
                        upid: 2116
                    },
                    {
                        name: "辰溪县",
                        id: 2121,
                        upid: 2116
                    },
                    {
                        name: "溆浦县",
                        id: 2122,
                        upid: 2116
                    },
                    {
                        name: "会同县",
                        id: 2123,
                        upid: 2116
                    },
                    {
                        name: "麻阳苗族自治县",
                        id: 2124,
                        upid: 2116
                    },
                    {
                        name: "新晃侗族自治县",
                        id: 2125,
                        upid: 2116
                    },
                    {
                        name: "芷江侗族自治县",
                        id: 2126,
                        upid: 2116
                    },
                    {
                        name: "靖州苗族侗族自治县",
                        id: 2127,
                        upid: 2116
                    },
                    {
                        name: "通道侗族自治县",
                        id: 2128,
                        upid: 2116
                    },
                    {
                        name: "洪江市",
                        id: 2129,
                        upid: 2116
                    }
                ]
            },
            {
                name: "娄底市",
                id: 2130,
                upid: 1996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2131,
                        upid: 2130
                    },
                    {
                        name: "娄星区",
                        id: 2132,
                        upid: 2130
                    },
                    {
                        name: "双峰县",
                        id: 2133,
                        upid: 2130
                    },
                    {
                        name: "新化县",
                        id: 2134,
                        upid: 2130
                    },
                    {
                        name: "冷水江市",
                        id: 2135,
                        upid: 2130
                    },
                    {
                        name: "涟源市",
                        id: 2136,
                        upid: 2130
                    }
                ]
            },
            {
                name: "湘西土家族苗族自治州",
                id: 2137,
                upid: 1996,
                sub: [
                    {
                        name: "吉首市",
                        id: 2138,
                        upid: 2137
                    },
                    {
                        name: "泸溪县",
                        id: 2139,
                        upid: 2137
                    },
                    {
                        name: "凤凰县",
                        id: 2140,
                        upid: 2137
                    },
                    {
                        name: "花垣县",
                        id: 2141,
                        upid: 2137
                    },
                    {
                        name: "保靖县",
                        id: 2142,
                        upid: 2137
                    },
                    {
                        name: "古丈县",
                        id: 2143,
                        upid: 2137
                    },
                    {
                        name: "永顺县",
                        id: 2144,
                        upid: 2137
                    },
                    {
                        name: "龙山县",
                        id: 2145,
                        upid: 2137
                    }
                ]
            }
        ]
    },
    {
        name: "广东",
        id: 2146,
        upid: 0,
        sub: [
            {
                name: "广州市",
                id: 2147,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2148,
                        upid: 2147
                    },
                    {
                        name: "东山区",
                        id: 2149,
                        upid: 2147
                    },
                    {
                        name: "荔湾区",
                        id: 2150,
                        upid: 2147
                    },
                    {
                        name: "越秀区",
                        id: 2151,
                        upid: 2147
                    },
                    {
                        name: "海珠区",
                        id: 2152,
                        upid: 2147
                    },
                    {
                        name: "天河区",
                        id: 2153,
                        upid: 2147
                    },
                    {
                        name: "芳村区",
                        id: 2154,
                        upid: 2147
                    },
                    {
                        name: "白云区",
                        id: 2155,
                        upid: 2147
                    },
                    {
                        name: "黄埔区",
                        id: 2156,
                        upid: 2147
                    },
                    {
                        name: "番禺区",
                        id: 2157,
                        upid: 2147
                    },
                    {
                        name: "花都区",
                        id: 2158,
                        upid: 2147
                    },
                    {
                        name: "增城市",
                        id: 2159,
                        upid: 2147
                    },
                    {
                        name: "从化市",
                        id: 2160,
                        upid: 2147
                    }
                ]
            },
            {
                name: "韶关市",
                id: 2161,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2162,
                        upid: 2161
                    },
                    {
                        name: "北江区",
                        id: 2163,
                        upid: 2161
                    },
                    {
                        name: "武江区",
                        id: 2164,
                        upid: 2161
                    },
                    {
                        name: "浈江区",
                        id: 2165,
                        upid: 2161
                    },
                    {
                        name: "曲江县",
                        id: 2166,
                        upid: 2161
                    },
                    {
                        name: "始兴县",
                        id: 2167,
                        upid: 2161
                    },
                    {
                        name: "仁化县",
                        id: 2168,
                        upid: 2161
                    },
                    {
                        name: "翁源县",
                        id: 2169,
                        upid: 2161
                    },
                    {
                        name: "乳源瑶族自治县",
                        id: 2170,
                        upid: 2161
                    },
                    {
                        name: "新丰县",
                        id: 2171,
                        upid: 2161
                    },
                    {
                        name: "乐昌市",
                        id: 2172,
                        upid: 2161
                    },
                    {
                        name: "南雄市",
                        id: 2173,
                        upid: 2161
                    }
                ]
            },
            {
                name: "深圳市",
                id: 2174,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2175,
                        upid: 2174
                    },
                    {
                        name: "罗湖区",
                        id: 2176,
                        upid: 2174
                    },
                    {
                        name: "福田区",
                        id: 2177,
                        upid: 2174
                    },
                    {
                        name: "南山区",
                        id: 2178,
                        upid: 2174
                    },
                    {
                        name: "宝安区",
                        id: 2179,
                        upid: 2174
                    },
                    {
                        name: "龙岗区",
                        id: 2180,
                        upid: 2174
                    },
                    {
                        name: "盐田区",
                        id: 2181,
                        upid: 2174
                    }
                ]
            },
            {
                name: "珠海市",
                id: 2182,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2183,
                        upid: 2182
                    },
                    {
                        name: "香洲区",
                        id: 2184,
                        upid: 2182
                    },
                    {
                        name: "斗门区",
                        id: 2185,
                        upid: 2182
                    },
                    {
                        name: "金湾区",
                        id: 2186,
                        upid: 2182
                    }
                ]
            },
            {
                name: "汕头市",
                id: 2187,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2188,
                        upid: 2187
                    },
                    {
                        name: "龙湖区",
                        id: 2189,
                        upid: 2187
                    },
                    {
                        name: "金平区",
                        id: 2190,
                        upid: 2187
                    },
                    {
                        name: "濠江区",
                        id: 2191,
                        upid: 2187
                    },
                    {
                        name: "潮阳区",
                        id: 2192,
                        upid: 2187
                    },
                    {
                        name: "潮南区",
                        id: 2193,
                        upid: 2187
                    },
                    {
                        name: "澄海区",
                        id: 2194,
                        upid: 2187
                    },
                    {
                        name: "南澳县",
                        id: 2195,
                        upid: 2187
                    }
                ]
            },
            {
                name: "佛山市",
                id: 2196,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2197,
                        upid: 2196
                    },
                    {
                        name: "禅城区",
                        id: 2198,
                        upid: 2196
                    },
                    {
                        name: "南海区",
                        id: 2199,
                        upid: 2196
                    },
                    {
                        name: "顺德区",
                        id: 2200,
                        upid: 2196
                    },
                    {
                        name: "三水区",
                        id: 2201,
                        upid: 2196
                    },
                    {
                        name: "高明区",
                        id: 2202,
                        upid: 2196
                    }
                ]
            },
            {
                name: "江门市",
                id: 2203,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2204,
                        upid: 2203
                    },
                    {
                        name: "蓬江区",
                        id: 2205,
                        upid: 2203
                    },
                    {
                        name: "江海区",
                        id: 2206,
                        upid: 2203
                    },
                    {
                        name: "新会区",
                        id: 2207,
                        upid: 2203
                    },
                    {
                        name: "台山市",
                        id: 2208,
                        upid: 2203
                    },
                    {
                        name: "开平市",
                        id: 2209,
                        upid: 2203
                    },
                    {
                        name: "鹤山市",
                        id: 2210,
                        upid: 2203
                    },
                    {
                        name: "恩平市",
                        id: 2211,
                        upid: 2203
                    }
                ]
            },
            {
                name: "湛江市",
                id: 2212,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2213,
                        upid: 2212
                    },
                    {
                        name: "赤坎区",
                        id: 2214,
                        upid: 2212
                    },
                    {
                        name: "霞山区",
                        id: 2215,
                        upid: 2212
                    },
                    {
                        name: "坡头区",
                        id: 2216,
                        upid: 2212
                    },
                    {
                        name: "麻章区",
                        id: 2217,
                        upid: 2212
                    },
                    {
                        name: "遂溪县",
                        id: 2218,
                        upid: 2212
                    },
                    {
                        name: "徐闻县",
                        id: 2219,
                        upid: 2212
                    },
                    {
                        name: "廉江市",
                        id: 2220,
                        upid: 2212
                    },
                    {
                        name: "雷州市",
                        id: 2221,
                        upid: 2212
                    },
                    {
                        name: "吴川市",
                        id: 2222,
                        upid: 2212
                    }
                ]
            },
            {
                name: "茂名市",
                id: 2223,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2224,
                        upid: 2223
                    },
                    {
                        name: "茂南区",
                        id: 2225,
                        upid: 2223
                    },
                    {
                        name: "茂港区",
                        id: 2226,
                        upid: 2223
                    },
                    {
                        name: "电白县",
                        id: 2227,
                        upid: 2223
                    },
                    {
                        name: "高州市",
                        id: 2228,
                        upid: 2223
                    },
                    {
                        name: "化州市",
                        id: 2229,
                        upid: 2223
                    },
                    {
                        name: "信宜市",
                        id: 2230,
                        upid: 2223
                    }
                ]
            },
            {
                name: "肇庆市",
                id: 2231,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2232,
                        upid: 2231
                    },
                    {
                        name: "端州区",
                        id: 2233,
                        upid: 2231
                    },
                    {
                        name: "鼎湖区",
                        id: 2234,
                        upid: 2231
                    },
                    {
                        name: "广宁县",
                        id: 2235,
                        upid: 2231
                    },
                    {
                        name: "怀集县",
                        id: 2236,
                        upid: 2231
                    },
                    {
                        name: "封开县",
                        id: 2237,
                        upid: 2231
                    },
                    {
                        name: "德庆县",
                        id: 2238,
                        upid: 2231
                    },
                    {
                        name: "高要市",
                        id: 2239,
                        upid: 2231
                    },
                    {
                        name: "四会市",
                        id: 2240,
                        upid: 2231
                    }
                ]
            },
            {
                name: "惠州市",
                id: 2241,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2242,
                        upid: 2241
                    },
                    {
                        name: "惠城区",
                        id: 2243,
                        upid: 2241
                    },
                    {
                        name: "惠阳区",
                        id: 2244,
                        upid: 2241
                    },
                    {
                        name: "博罗县",
                        id: 2245,
                        upid: 2241
                    },
                    {
                        name: "惠东县",
                        id: 2246,
                        upid: 2241
                    },
                    {
                        name: "龙门县",
                        id: 2247,
                        upid: 2241
                    }
                ]
            },
            {
                name: "梅州市",
                id: 2248,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2249,
                        upid: 2248
                    },
                    {
                        name: "梅江区",
                        id: 2250,
                        upid: 2248
                    },
                    {
                        name: "梅县",
                        id: 2251,
                        upid: 2248
                    },
                    {
                        name: "大埔县",
                        id: 2252,
                        upid: 2248
                    },
                    {
                        name: "丰顺县",
                        id: 2253,
                        upid: 2248
                    },
                    {
                        name: "五华县",
                        id: 2254,
                        upid: 2248
                    },
                    {
                        name: "平远县",
                        id: 2255,
                        upid: 2248
                    },
                    {
                        name: "蕉岭县",
                        id: 2256,
                        upid: 2248
                    },
                    {
                        name: "兴宁市",
                        id: 2257,
                        upid: 2248
                    }
                ]
            },
            {
                name: "汕尾市",
                id: 2258,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2259,
                        upid: 2258
                    },
                    {
                        name: "城区",
                        id: 2260,
                        upid: 2258
                    },
                    {
                        name: "海丰县",
                        id: 2261,
                        upid: 2258
                    },
                    {
                        name: "陆河县",
                        id: 2262,
                        upid: 2258
                    },
                    {
                        name: "陆丰市",
                        id: 2263,
                        upid: 2258
                    }
                ]
            },
            {
                name: "河源市",
                id: 2264,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2265,
                        upid: 2264
                    },
                    {
                        name: "源城区",
                        id: 2266,
                        upid: 2264
                    },
                    {
                        name: "紫金县",
                        id: 2267,
                        upid: 2264
                    },
                    {
                        name: "龙川县",
                        id: 2268,
                        upid: 2264
                    },
                    {
                        name: "连平县",
                        id: 2269,
                        upid: 2264
                    },
                    {
                        name: "和平县",
                        id: 2270,
                        upid: 2264
                    },
                    {
                        name: "东源县",
                        id: 2271,
                        upid: 2264
                    }
                ]
            },
            {
                name: "阳江市",
                id: 2272,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2273,
                        upid: 2272
                    },
                    {
                        name: "江城区",
                        id: 2274,
                        upid: 2272
                    },
                    {
                        name: "阳西县",
                        id: 2275,
                        upid: 2272
                    },
                    {
                        name: "阳东县",
                        id: 2276,
                        upid: 2272
                    },
                    {
                        name: "阳春市",
                        id: 2277,
                        upid: 2272
                    }
                ]
            },
            {
                name: "清远市",
                id: 2278,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2279,
                        upid: 2278
                    },
                    {
                        name: "清城区",
                        id: 2280,
                        upid: 2278
                    },
                    {
                        name: "佛冈县",
                        id: 2281,
                        upid: 2278
                    },
                    {
                        name: "阳山县",
                        id: 2282,
                        upid: 2278
                    },
                    {
                        name: "连山壮族瑶族自治县",
                        id: 2283,
                        upid: 2278
                    },
                    {
                        name: "连南瑶族自治县",
                        id: 2284,
                        upid: 2278
                    },
                    {
                        name: "清新县",
                        id: 2285,
                        upid: 2278
                    },
                    {
                        name: "英德市",
                        id: 2286,
                        upid: 2278
                    },
                    {
                        name: "连州市",
                        id: 2287,
                        upid: 2278
                    }
                ]
            },
            {
                name: "东莞市",
                id: 2288,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 46175,
                        upid: 2288
                    }
                ]
            },
            {
                name: "中山市",
                id: 2289,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 46174,
                        upid: 2289
                    }
                ]
            },
            {
                name: "潮州市",
                id: 2290,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2291,
                        upid: 2290
                    },
                    {
                        name: "湘桥区",
                        id: 2292,
                        upid: 2290
                    },
                    {
                        name: "潮安县",
                        id: 2293,
                        upid: 2290
                    },
                    {
                        name: "饶平县",
                        id: 2294,
                        upid: 2290
                    }
                ]
            },
            {
                name: "揭阳市",
                id: 2295,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2296,
                        upid: 2295
                    },
                    {
                        name: "榕城区",
                        id: 2297,
                        upid: 2295
                    },
                    {
                        name: "揭东县",
                        id: 2298,
                        upid: 2295
                    },
                    {
                        name: "揭西县",
                        id: 2299,
                        upid: 2295
                    },
                    {
                        name: "惠来县",
                        id: 2300,
                        upid: 2295
                    },
                    {
                        name: "普宁市",
                        id: 2301,
                        upid: 2295
                    }
                ]
            },
            {
                name: "云浮市",
                id: 2302,
                upid: 2146,
                sub: [
                    {
                        name: "市辖区",
                        id: 2303,
                        upid: 2302
                    },
                    {
                        name: "云城区",
                        id: 2304,
                        upid: 2302
                    },
                    {
                        name: "新兴县",
                        id: 2305,
                        upid: 2302
                    },
                    {
                        name: "郁南县",
                        id: 2306,
                        upid: 2302
                    },
                    {
                        name: "云安县",
                        id: 2307,
                        upid: 2302
                    },
                    {
                        name: "罗定市",
                        id: 2308,
                        upid: 2302
                    }
                ]
            }
        ]
    },
    {
        name: "广西",
        id: 2309,
        upid: 0,
        sub: [
            {
                name: "南宁市",
                id: 2310,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2311,
                        upid: 2310
                    },
                    {
                        name: "兴宁区",
                        id: 2312,
                        upid: 2310
                    },
                    {
                        name: "新城区",
                        id: 2313,
                        upid: 2310
                    },
                    {
                        name: "城北区",
                        id: 2314,
                        upid: 2310
                    },
                    {
                        name: "江南区",
                        id: 2315,
                        upid: 2310
                    },
                    {
                        name: "永新区",
                        id: 2316,
                        upid: 2310
                    },
                    {
                        name: "邕宁县",
                        id: 2317,
                        upid: 2310
                    },
                    {
                        name: "武鸣县",
                        id: 2318,
                        upid: 2310
                    },
                    {
                        name: "隆安县",
                        id: 2319,
                        upid: 2310
                    },
                    {
                        name: "马山县",
                        id: 2320,
                        upid: 2310
                    },
                    {
                        name: "上林县",
                        id: 2321,
                        upid: 2310
                    },
                    {
                        name: "宾阳县",
                        id: 2322,
                        upid: 2310
                    },
                    {
                        name: "横县",
                        id: 2323,
                        upid: 2310
                    }
                ]
            },
            {
                name: "柳州市",
                id: 2324,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2325,
                        upid: 2324
                    },
                    {
                        name: "城中区",
                        id: 2326,
                        upid: 2324
                    },
                    {
                        name: "鱼峰区",
                        id: 2327,
                        upid: 2324
                    },
                    {
                        name: "柳南区",
                        id: 2328,
                        upid: 2324
                    },
                    {
                        name: "柳北区",
                        id: 2329,
                        upid: 2324
                    },
                    {
                        name: "柳江县",
                        id: 2330,
                        upid: 2324
                    },
                    {
                        name: "柳城县",
                        id: 2331,
                        upid: 2324
                    },
                    {
                        name: "鹿寨县",
                        id: 2332,
                        upid: 2324
                    },
                    {
                        name: "融安县",
                        id: 2333,
                        upid: 2324
                    },
                    {
                        name: "融水苗族自治县",
                        id: 2334,
                        upid: 2324
                    },
                    {
                        name: "三江侗族自治县",
                        id: 2335,
                        upid: 2324
                    }
                ]
            },
            {
                name: "桂林市",
                id: 2336,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2337,
                        upid: 2336
                    },
                    {
                        name: "秀峰区",
                        id: 2338,
                        upid: 2336
                    },
                    {
                        name: "叠彩区",
                        id: 2339,
                        upid: 2336
                    },
                    {
                        name: "象山区",
                        id: 2340,
                        upid: 2336
                    },
                    {
                        name: "七星区",
                        id: 2341,
                        upid: 2336
                    },
                    {
                        name: "雁山区",
                        id: 2342,
                        upid: 2336
                    },
                    {
                        name: "阳朔县",
                        id: 2343,
                        upid: 2336
                    },
                    {
                        name: "临桂县",
                        id: 2344,
                        upid: 2336
                    },
                    {
                        name: "灵川县",
                        id: 2345,
                        upid: 2336
                    },
                    {
                        name: "全州县",
                        id: 2346,
                        upid: 2336
                    },
                    {
                        name: "兴安县",
                        id: 2347,
                        upid: 2336
                    },
                    {
                        name: "永福县",
                        id: 2348,
                        upid: 2336
                    },
                    {
                        name: "灌阳县",
                        id: 2349,
                        upid: 2336
                    },
                    {
                        name: "龙胜各族自治县",
                        id: 2350,
                        upid: 2336
                    },
                    {
                        name: "资源县",
                        id: 2351,
                        upid: 2336
                    },
                    {
                        name: "平乐县",
                        id: 2352,
                        upid: 2336
                    },
                    {
                        name: "荔蒲县",
                        id: 2353,
                        upid: 2336
                    },
                    {
                        name: "恭城瑶族自治县",
                        id: 2354,
                        upid: 2336
                    }
                ]
            },
            {
                name: "梧州市",
                id: 2355,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2356,
                        upid: 2355
                    },
                    {
                        name: "万秀区",
                        id: 2357,
                        upid: 2355
                    },
                    {
                        name: "蝶山区",
                        id: 2358,
                        upid: 2355
                    },
                    {
                        name: "长洲区",
                        id: 2359,
                        upid: 2355
                    },
                    {
                        name: "苍梧县",
                        id: 2360,
                        upid: 2355
                    },
                    {
                        name: "藤县",
                        id: 2361,
                        upid: 2355
                    },
                    {
                        name: "蒙山县",
                        id: 2362,
                        upid: 2355
                    },
                    {
                        name: "岑溪市",
                        id: 2363,
                        upid: 2355
                    }
                ]
            },
            {
                name: "北海市",
                id: 2364,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2365,
                        upid: 2364
                    },
                    {
                        name: "海城区",
                        id: 2366,
                        upid: 2364
                    },
                    {
                        name: "银海区",
                        id: 2367,
                        upid: 2364
                    },
                    {
                        name: "铁山港区",
                        id: 2368,
                        upid: 2364
                    },
                    {
                        name: "合浦县",
                        id: 2369,
                        upid: 2364
                    }
                ]
            },
            {
                name: "防城港市",
                id: 2370,
                upid: 2309,
                sub: [
                    {
                        name: "辖区",
                        id: 2371,
                        upid: 2370
                    },
                    {
                        name: "防城港港口区",
                        id: 2372,
                        upid: 2370
                    },
                    {
                        name: "防城港防城区",
                        id: 2373,
                        upid: 2370
                    },
                    {
                        name: "上思县",
                        id: 2374,
                        upid: 2370
                    },
                    {
                        name: "东兴市",
                        id: 2375,
                        upid: 2370
                    }
                ]
            },
            {
                name: "钦州市",
                id: 2376,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2377,
                        upid: 2376
                    },
                    {
                        name: "钦南区",
                        id: 2378,
                        upid: 2376
                    },
                    {
                        name: "钦北区",
                        id: 2379,
                        upid: 2376
                    },
                    {
                        name: "灵山县",
                        id: 2380,
                        upid: 2376
                    },
                    {
                        name: "浦北县",
                        id: 2381,
                        upid: 2376
                    }
                ]
            },
            {
                name: "贵港市",
                id: 2382,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2383,
                        upid: 2382
                    },
                    {
                        name: "港北区",
                        id: 2384,
                        upid: 2382
                    },
                    {
                        name: "港南区",
                        id: 2385,
                        upid: 2382
                    },
                    {
                        name: "覃塘区",
                        id: 2386,
                        upid: 2382
                    },
                    {
                        name: "平南县",
                        id: 2387,
                        upid: 2382
                    },
                    {
                        name: "桂平市",
                        id: 2388,
                        upid: 2382
                    }
                ]
            },
            {
                name: "玉林市",
                id: 2389,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2390,
                        upid: 2389
                    },
                    {
                        name: "玉州区",
                        id: 2391,
                        upid: 2389
                    },
                    {
                        name: "容县",
                        id: 2392,
                        upid: 2389
                    },
                    {
                        name: "陆川县",
                        id: 2393,
                        upid: 2389
                    },
                    {
                        name: "博白县",
                        id: 2394,
                        upid: 2389
                    },
                    {
                        name: "兴业县",
                        id: 2395,
                        upid: 2389
                    },
                    {
                        name: "北流市",
                        id: 2396,
                        upid: 2389
                    }
                ]
            },
            {
                name: "百色市",
                id: 2397,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2398,
                        upid: 2397
                    },
                    {
                        name: "右江区",
                        id: 2399,
                        upid: 2397
                    },
                    {
                        name: "田阳县",
                        id: 2400,
                        upid: 2397
                    },
                    {
                        name: "田东县",
                        id: 2401,
                        upid: 2397
                    },
                    {
                        name: "平果县",
                        id: 2402,
                        upid: 2397
                    },
                    {
                        name: "德保县",
                        id: 2403,
                        upid: 2397
                    },
                    {
                        name: "靖西县",
                        id: 2404,
                        upid: 2397
                    },
                    {
                        name: "那坡县",
                        id: 2405,
                        upid: 2397
                    },
                    {
                        name: "凌云县",
                        id: 2406,
                        upid: 2397
                    },
                    {
                        name: "乐业县",
                        id: 2407,
                        upid: 2397
                    },
                    {
                        name: "田林县",
                        id: 2408,
                        upid: 2397
                    },
                    {
                        name: "西林县",
                        id: 2409,
                        upid: 2397
                    },
                    {
                        name: "隆林各族自治县",
                        id: 2410,
                        upid: 2397
                    }
                ]
            },
            {
                name: "贺州市",
                id: 2411,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2412,
                        upid: 2411
                    },
                    {
                        name: "八步区",
                        id: 2413,
                        upid: 2411
                    },
                    {
                        name: "昭平县",
                        id: 2414,
                        upid: 2411
                    },
                    {
                        name: "钟山县",
                        id: 2415,
                        upid: 2411
                    },
                    {
                        name: "富川瑶族自治县",
                        id: 2416,
                        upid: 2411
                    }
                ]
            },
            {
                name: "河池市",
                id: 2417,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2418,
                        upid: 2417
                    },
                    {
                        name: "金城江区",
                        id: 2419,
                        upid: 2417
                    },
                    {
                        name: "南丹县",
                        id: 2420,
                        upid: 2417
                    },
                    {
                        name: "天峨县",
                        id: 2421,
                        upid: 2417
                    },
                    {
                        name: "凤山县",
                        id: 2422,
                        upid: 2417
                    },
                    {
                        name: "东兰县",
                        id: 2423,
                        upid: 2417
                    },
                    {
                        name: "罗城仫佬族自治县",
                        id: 2424,
                        upid: 2417
                    },
                    {
                        name: "环江毛南族自治县",
                        id: 2425,
                        upid: 2417
                    },
                    {
                        name: "巴马瑶族自治县",
                        id: 2426,
                        upid: 2417
                    },
                    {
                        name: "都安瑶族自治县",
                        id: 2427,
                        upid: 2417
                    },
                    {
                        name: "大化瑶族自治县",
                        id: 2428,
                        upid: 2417
                    },
                    {
                        name: "宜州市",
                        id: 2429,
                        upid: 2417
                    }
                ]
            },
            {
                name: "来宾市",
                id: 2430,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2431,
                        upid: 2430
                    },
                    {
                        name: "兴宾区",
                        id: 2432,
                        upid: 2430
                    },
                    {
                        name: "忻城县",
                        id: 2433,
                        upid: 2430
                    },
                    {
                        name: "象州县",
                        id: 2434,
                        upid: 2430
                    },
                    {
                        name: "武宣县",
                        id: 2435,
                        upid: 2430
                    },
                    {
                        name: "金秀瑶族自治县",
                        id: 2436,
                        upid: 2430
                    },
                    {
                        name: "合山市",
                        id: 2437,
                        upid: 2430
                    }
                ]
            },
            {
                name: "崇左市",
                id: 2438,
                upid: 2309,
                sub: [
                    {
                        name: "市辖区",
                        id: 2439,
                        upid: 2438
                    },
                    {
                        name: "江洲区",
                        id: 2440,
                        upid: 2438
                    },
                    {
                        name: "扶绥县",
                        id: 2441,
                        upid: 2438
                    },
                    {
                        name: "宁明县",
                        id: 2442,
                        upid: 2438
                    },
                    {
                        name: "龙州县",
                        id: 2443,
                        upid: 2438
                    },
                    {
                        name: "大新县",
                        id: 2444,
                        upid: 2438
                    },
                    {
                        name: "天等县",
                        id: 2445,
                        upid: 2438
                    },
                    {
                        name: "凭祥市",
                        id: 2446,
                        upid: 2438
                    }
                ]
            },
            {
                name: "广西",
                id: 46183,
                upid: 2309,
                sub: [ ]
            }
        ]
    },
    {
        name: "海南",
        id: 2447,
        upid: 0,
        sub: [
            {
                name: "海口市",
                id: 2448,
                upid: 2447,
                sub: [
                    {
                        name: "市辖区",
                        id: 2449,
                        upid: 2448
                    },
                    {
                        name: "秀英区",
                        id: 2450,
                        upid: 2448
                    },
                    {
                        name: "龙华区",
                        id: 2451,
                        upid: 2448
                    },
                    {
                        name: "琼山区",
                        id: 2452,
                        upid: 2448
                    },
                    {
                        name: "美兰区",
                        id: 2453,
                        upid: 2448
                    }
                ]
            },
            {
                name: "三亚市",
                id: 2454,
                upid: 2447,
                sub: [
                    {
                        name: "市辖区",
                        id: 2455,
                        upid: 2454
                    }
                ]
            },
            {
                name: "省直辖县级行政单位",
                id: 2456,
                upid: 2447,
                sub: [
                    {
                        name: "五指山市",
                        id: 2457,
                        upid: 2456
                    },
                    {
                        name: "琼海市",
                        id: 2458,
                        upid: 2456
                    },
                    {
                        name: "儋州市",
                        id: 2459,
                        upid: 2456
                    },
                    {
                        name: "文昌市",
                        id: 2460,
                        upid: 2456
                    },
                    {
                        name: "万宁市",
                        id: 2461,
                        upid: 2456
                    },
                    {
                        name: "东方市",
                        id: 2462,
                        upid: 2456
                    },
                    {
                        name: "定安县",
                        id: 2463,
                        upid: 2456
                    },
                    {
                        name: "屯昌县",
                        id: 2464,
                        upid: 2456
                    },
                    {
                        name: "澄迈县",
                        id: 2465,
                        upid: 2456
                    },
                    {
                        name: "临高县",
                        id: 2466,
                        upid: 2456
                    },
                    {
                        name: "白沙黎族自治县",
                        id: 2467,
                        upid: 2456
                    },
                    {
                        name: "昌江黎族自治县",
                        id: 2468,
                        upid: 2456
                    },
                    {
                        name: "乐东黎族自治县",
                        id: 2469,
                        upid: 2456
                    },
                    {
                        name: "陵水黎族自治县",
                        id: 2470,
                        upid: 2456
                    },
                    {
                        name: "保亭黎族苗族自治县",
                        id: 2471,
                        upid: 2456
                    },
                    {
                        name: "琼中黎族苗族自治县",
                        id: 2472,
                        upid: 2456
                    },
                    {
                        name: "西沙群岛",
                        id: 2473,
                        upid: 2456
                    },
                    {
                        name: "南沙群岛",
                        id: 2474,
                        upid: 2456
                    },
                    {
                        name: "中沙群岛的岛礁及其海域",
                        id: 2475,
                        upid: 2456
                    }
                ]
            }
        ]
    },
    {
        name: "重庆",
        id: 2476,
        upid: 0,
        sub: [
            {
                name: "市辖区",
                id: 2477,
                upid: 2476,
                sub: [
                    {
                        name: "万州区",
                        id: 2478,
                        upid: 2477
                    },
                    {
                        name: "涪陵区",
                        id: 2479,
                        upid: 2477
                    },
                    {
                        name: "渝中区",
                        id: 2480,
                        upid: 2477
                    },
                    {
                        name: "大渡口区",
                        id: 2481,
                        upid: 2477
                    },
                    {
                        name: "江北区",
                        id: 2482,
                        upid: 2477
                    },
                    {
                        name: "沙坪坝区",
                        id: 2483,
                        upid: 2477
                    },
                    {
                        name: "九龙坡区",
                        id: 2484,
                        upid: 2477
                    },
                    {
                        name: "南岸区",
                        id: 2485,
                        upid: 2477
                    },
                    {
                        name: "北碚区",
                        id: 2486,
                        upid: 2477
                    },
                    {
                        name: "万盛区",
                        id: 2487,
                        upid: 2477
                    },
                    {
                        name: "双桥区",
                        id: 2488,
                        upid: 2477
                    },
                    {
                        name: "渝北区",
                        id: 2489,
                        upid: 2477
                    },
                    {
                        name: "巴南区",
                        id: 2490,
                        upid: 2477
                    },
                    {
                        name: "黔江区",
                        id: 2491,
                        upid: 2477
                    },
                    {
                        name: "长寿区",
                        id: 2492,
                        upid: 2477
                    }
                ]
            },
            {
                name: "县",
                id: 2493,
                upid: 2476,
                sub: [
                    {
                        name: "綦江",
                        id: 2494,
                        upid: 2493
                    },
                    {
                        name: "潼南",
                        id: 2495,
                        upid: 2493
                    },
                    {
                        name: "铜梁",
                        id: 2496,
                        upid: 2493
                    },
                    {
                        name: "大足",
                        id: 2497,
                        upid: 2493
                    },
                    {
                        name: "荣昌",
                        id: 2498,
                        upid: 2493
                    },
                    {
                        name: "璧山",
                        id: 2499,
                        upid: 2493
                    },
                    {
                        name: "梁平",
                        id: 2500,
                        upid: 2493
                    },
                    {
                        name: "城口",
                        id: 2501,
                        upid: 2493
                    },
                    {
                        name: "丰都",
                        id: 2502,
                        upid: 2493
                    },
                    {
                        name: "垫江",
                        id: 2503,
                        upid: 2493
                    },
                    {
                        name: "武隆",
                        id: 2504,
                        upid: 2493
                    },
                    {
                        name: "忠",
                        id: 2505,
                        upid: 2493
                    },
                    {
                        name: "开",
                        id: 2506,
                        upid: 2493
                    },
                    {
                        name: "云阳",
                        id: 2507,
                        upid: 2493
                    },
                    {
                        name: "奉节",
                        id: 2508,
                        upid: 2493
                    },
                    {
                        name: "巫山",
                        id: 2509,
                        upid: 2493
                    },
                    {
                        name: "巫溪",
                        id: 2510,
                        upid: 2493
                    },
                    {
                        name: "石柱土家族自治",
                        id: 2511,
                        upid: 2493
                    },
                    {
                        name: "秀山土家族苗族自治",
                        id: 2512,
                        upid: 2493
                    },
                    {
                        name: "酉阳土家族苗族自治",
                        id: 2513,
                        upid: 2493
                    },
                    {
                        name: "彭水苗族土家族自治",
                        id: 2514,
                        upid: 2493
                    }
                ]
            },
            {
                name: "市",
                id: 2515,
                upid: 2476,
                sub: [
                    {
                        name: "江津",
                        id: 2516,
                        upid: 2515
                    },
                    {
                        name: "合川",
                        id: 2517,
                        upid: 2515
                    },
                    {
                        name: "永川",
                        id: 2518,
                        upid: 2515
                    },
                    {
                        name: "南川",
                        id: 2519,
                        upid: 2515
                    }
                ]
            }
        ]
    },
    {
        name: "四川",
        id: 2520,
        upid: 0,
        sub: [
            {
                name: "成都市",
                id: 2521,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2522,
                        upid: 2521
                    },
                    {
                        name: "锦江区",
                        id: 2523,
                        upid: 2521
                    },
                    {
                        name: "青羊区",
                        id: 2524,
                        upid: 2521
                    },
                    {
                        name: "金牛区",
                        id: 2525,
                        upid: 2521
                    },
                    {
                        name: "武侯区",
                        id: 2526,
                        upid: 2521
                    },
                    {
                        name: "成华区",
                        id: 2527,
                        upid: 2521
                    },
                    {
                        name: "龙泉驿区",
                        id: 2528,
                        upid: 2521
                    },
                    {
                        name: "青白江区",
                        id: 2529,
                        upid: 2521
                    },
                    {
                        name: "新都区",
                        id: 2530,
                        upid: 2521
                    },
                    {
                        name: "温江区",
                        id: 2531,
                        upid: 2521
                    },
                    {
                        name: "金堂县",
                        id: 2532,
                        upid: 2521
                    },
                    {
                        name: "双流县",
                        id: 2533,
                        upid: 2521
                    },
                    {
                        name: "郫县",
                        id: 2534,
                        upid: 2521
                    },
                    {
                        name: "大邑县",
                        id: 2535,
                        upid: 2521
                    },
                    {
                        name: "蒲江县",
                        id: 2536,
                        upid: 2521
                    },
                    {
                        name: "新津县",
                        id: 2537,
                        upid: 2521
                    },
                    {
                        name: "都江堰市",
                        id: 2538,
                        upid: 2521
                    },
                    {
                        name: "彭州市",
                        id: 2539,
                        upid: 2521
                    },
                    {
                        name: "邛崃市",
                        id: 2540,
                        upid: 2521
                    },
                    {
                        name: "崇州市",
                        id: 2541,
                        upid: 2521
                    }
                ]
            },
            {
                name: "自贡市",
                id: 2542,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2543,
                        upid: 2542
                    },
                    {
                        name: "自流井区",
                        id: 2544,
                        upid: 2542
                    },
                    {
                        name: "贡井区",
                        id: 2545,
                        upid: 2542
                    },
                    {
                        name: "大安区",
                        id: 2546,
                        upid: 2542
                    },
                    {
                        name: "沿滩区",
                        id: 2547,
                        upid: 2542
                    },
                    {
                        name: "荣县",
                        id: 2548,
                        upid: 2542
                    },
                    {
                        name: "富顺县",
                        id: 2549,
                        upid: 2542
                    }
                ]
            },
            {
                name: "攀枝花市",
                id: 2550,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2551,
                        upid: 2550
                    },
                    {
                        name: "东区",
                        id: 2552,
                        upid: 2550
                    },
                    {
                        name: "西区",
                        id: 2553,
                        upid: 2550
                    },
                    {
                        name: "仁和区",
                        id: 2554,
                        upid: 2550
                    },
                    {
                        name: "米易县",
                        id: 2555,
                        upid: 2550
                    },
                    {
                        name: "盐边县",
                        id: 2556,
                        upid: 2550
                    }
                ]
            },
            {
                name: "泸州市",
                id: 2557,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2558,
                        upid: 2557
                    },
                    {
                        name: "江阳区",
                        id: 2559,
                        upid: 2557
                    },
                    {
                        name: "纳溪区",
                        id: 2560,
                        upid: 2557
                    },
                    {
                        name: "龙马潭区",
                        id: 2561,
                        upid: 2557
                    },
                    {
                        name: "泸县",
                        id: 2562,
                        upid: 2557
                    },
                    {
                        name: "合江县",
                        id: 2563,
                        upid: 2557
                    },
                    {
                        name: "叙永县",
                        id: 2564,
                        upid: 2557
                    },
                    {
                        name: "古蔺县",
                        id: 2565,
                        upid: 2557
                    }
                ]
            },
            {
                name: "德阳市",
                id: 2566,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2567,
                        upid: 2566
                    },
                    {
                        name: "旌阳区",
                        id: 2568,
                        upid: 2566
                    },
                    {
                        name: "中江县",
                        id: 2569,
                        upid: 2566
                    },
                    {
                        name: "罗江县",
                        id: 2570,
                        upid: 2566
                    },
                    {
                        name: "广汉市",
                        id: 2571,
                        upid: 2566
                    },
                    {
                        name: "什邡市",
                        id: 2572,
                        upid: 2566
                    },
                    {
                        name: "绵竹市",
                        id: 2573,
                        upid: 2566
                    }
                ]
            },
            {
                name: "绵阳市",
                id: 2574,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2575,
                        upid: 2574
                    },
                    {
                        name: "涪城区",
                        id: 2576,
                        upid: 2574
                    },
                    {
                        name: "游仙区",
                        id: 2577,
                        upid: 2574
                    },
                    {
                        name: "三台县",
                        id: 2578,
                        upid: 2574
                    },
                    {
                        name: "盐亭县",
                        id: 2579,
                        upid: 2574
                    },
                    {
                        name: "安县",
                        id: 2580,
                        upid: 2574
                    },
                    {
                        name: "梓潼县",
                        id: 2581,
                        upid: 2574
                    },
                    {
                        name: "北川羌族自治县",
                        id: 2582,
                        upid: 2574
                    },
                    {
                        name: "平武县",
                        id: 2583,
                        upid: 2574
                    },
                    {
                        name: "江油市",
                        id: 2584,
                        upid: 2574
                    }
                ]
            },
            {
                name: "广元市",
                id: 2585,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2586,
                        upid: 2585
                    },
                    {
                        name: "市中区",
                        id: 2587,
                        upid: 2585
                    },
                    {
                        name: "元坝区",
                        id: 2588,
                        upid: 2585
                    },
                    {
                        name: "朝天区",
                        id: 2589,
                        upid: 2585
                    },
                    {
                        name: "旺苍县",
                        id: 2590,
                        upid: 2585
                    },
                    {
                        name: "青川县",
                        id: 2591,
                        upid: 2585
                    },
                    {
                        name: "剑阁县",
                        id: 2592,
                        upid: 2585
                    },
                    {
                        name: "苍溪县",
                        id: 2593,
                        upid: 2585
                    }
                ]
            },
            {
                name: "遂宁市",
                id: 2594,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2595,
                        upid: 2594
                    },
                    {
                        name: "船山区",
                        id: 2596,
                        upid: 2594
                    },
                    {
                        name: "安居区",
                        id: 2597,
                        upid: 2594
                    },
                    {
                        name: "蓬溪县",
                        id: 2598,
                        upid: 2594
                    },
                    {
                        name: "射洪县",
                        id: 2599,
                        upid: 2594
                    },
                    {
                        name: "大英县",
                        id: 2600,
                        upid: 2594
                    }
                ]
            },
            {
                name: "内江市",
                id: 2601,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2602,
                        upid: 2601
                    },
                    {
                        name: "市中区",
                        id: 2603,
                        upid: 2601
                    },
                    {
                        name: "东兴区",
                        id: 2604,
                        upid: 2601
                    },
                    {
                        name: "威远县",
                        id: 2605,
                        upid: 2601
                    },
                    {
                        name: "资中县",
                        id: 2606,
                        upid: 2601
                    },
                    {
                        name: "隆昌县",
                        id: 2607,
                        upid: 2601
                    }
                ]
            },
            {
                name: "乐山市",
                id: 2608,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2609,
                        upid: 2608
                    },
                    {
                        name: "市中区",
                        id: 2610,
                        upid: 2608
                    },
                    {
                        name: "沙湾区",
                        id: 2611,
                        upid: 2608
                    },
                    {
                        name: "五通桥区",
                        id: 2612,
                        upid: 2608
                    },
                    {
                        name: "金口河区",
                        id: 2613,
                        upid: 2608
                    },
                    {
                        name: "犍为县",
                        id: 2614,
                        upid: 2608
                    },
                    {
                        name: "井研县",
                        id: 2615,
                        upid: 2608
                    },
                    {
                        name: "夹江县",
                        id: 2616,
                        upid: 2608
                    },
                    {
                        name: "沐川县",
                        id: 2617,
                        upid: 2608
                    },
                    {
                        name: "峨边彝族自治县",
                        id: 2618,
                        upid: 2608
                    },
                    {
                        name: "马边彝族自治县",
                        id: 2619,
                        upid: 2608
                    },
                    {
                        name: "峨眉山市",
                        id: 2620,
                        upid: 2608
                    }
                ]
            },
            {
                name: "南充市",
                id: 2621,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2622,
                        upid: 2621
                    },
                    {
                        name: "顺庆区",
                        id: 2623,
                        upid: 2621
                    },
                    {
                        name: "高坪区",
                        id: 2624,
                        upid: 2621
                    },
                    {
                        name: "嘉陵区",
                        id: 2625,
                        upid: 2621
                    },
                    {
                        name: "南部县",
                        id: 2626,
                        upid: 2621
                    },
                    {
                        name: "营山县",
                        id: 2627,
                        upid: 2621
                    },
                    {
                        name: "蓬安县",
                        id: 2628,
                        upid: 2621
                    },
                    {
                        name: "仪陇县",
                        id: 2629,
                        upid: 2621
                    },
                    {
                        name: "西充县",
                        id: 2630,
                        upid: 2621
                    },
                    {
                        name: "阆中市",
                        id: 2631,
                        upid: 2621
                    }
                ]
            },
            {
                name: "眉山市",
                id: 2632,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2633,
                        upid: 2632
                    },
                    {
                        name: "东坡区",
                        id: 2634,
                        upid: 2632
                    },
                    {
                        name: "仁寿县",
                        id: 2635,
                        upid: 2632
                    },
                    {
                        name: "彭山县",
                        id: 2636,
                        upid: 2632
                    },
                    {
                        name: "洪雅县",
                        id: 2637,
                        upid: 2632
                    },
                    {
                        name: "丹棱县",
                        id: 2638,
                        upid: 2632
                    },
                    {
                        name: "青神县",
                        id: 2639,
                        upid: 2632
                    }
                ]
            },
            {
                name: "宜宾市",
                id: 2640,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2641,
                        upid: 2640
                    },
                    {
                        name: "翠屏区",
                        id: 2642,
                        upid: 2640
                    },
                    {
                        name: "宜宾县",
                        id: 2643,
                        upid: 2640
                    },
                    {
                        name: "南溪县",
                        id: 2644,
                        upid: 2640
                    },
                    {
                        name: "江安县",
                        id: 2645,
                        upid: 2640
                    },
                    {
                        name: "长宁县",
                        id: 2646,
                        upid: 2640
                    },
                    {
                        name: "高县",
                        id: 2647,
                        upid: 2640
                    },
                    {
                        name: "珙县",
                        id: 2648,
                        upid: 2640
                    },
                    {
                        name: "筠连县",
                        id: 2649,
                        upid: 2640
                    },
                    {
                        name: "兴文县",
                        id: 2650,
                        upid: 2640
                    },
                    {
                        name: "屏山县",
                        id: 2651,
                        upid: 2640
                    }
                ]
            },
            {
                name: "广安市",
                id: 2652,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2653,
                        upid: 2652
                    },
                    {
                        name: "广安区",
                        id: 2654,
                        upid: 2652
                    },
                    {
                        name: "岳池县",
                        id: 2655,
                        upid: 2652
                    },
                    {
                        name: "武胜县",
                        id: 2656,
                        upid: 2652
                    },
                    {
                        name: "邻水县",
                        id: 2657,
                        upid: 2652
                    },
                    {
                        name: "华莹市",
                        id: 2658,
                        upid: 2652
                    }
                ]
            },
            {
                name: "达州市",
                id: 2659,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2660,
                        upid: 2659
                    },
                    {
                        name: "通川区",
                        id: 2661,
                        upid: 2659
                    },
                    {
                        name: "达县",
                        id: 2662,
                        upid: 2659
                    },
                    {
                        name: "宣汉县",
                        id: 2663,
                        upid: 2659
                    },
                    {
                        name: "开江县",
                        id: 2664,
                        upid: 2659
                    },
                    {
                        name: "大竹县",
                        id: 2665,
                        upid: 2659
                    },
                    {
                        name: "渠县",
                        id: 2666,
                        upid: 2659
                    },
                    {
                        name: "万源市",
                        id: 2667,
                        upid: 2659
                    }
                ]
            },
            {
                name: "雅安市",
                id: 2668,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2669,
                        upid: 2668
                    },
                    {
                        name: "雨城区",
                        id: 2670,
                        upid: 2668
                    },
                    {
                        name: "名山县",
                        id: 2671,
                        upid: 2668
                    },
                    {
                        name: "荥经县",
                        id: 2672,
                        upid: 2668
                    },
                    {
                        name: "汉源县",
                        id: 2673,
                        upid: 2668
                    },
                    {
                        name: "石棉县",
                        id: 2674,
                        upid: 2668
                    },
                    {
                        name: "天全县",
                        id: 2675,
                        upid: 2668
                    },
                    {
                        name: "芦山县",
                        id: 2676,
                        upid: 2668
                    },
                    {
                        name: "宝兴县",
                        id: 2677,
                        upid: 2668
                    }
                ]
            },
            {
                name: "巴中市",
                id: 2678,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2679,
                        upid: 2678
                    },
                    {
                        name: "巴州区",
                        id: 2680,
                        upid: 2678
                    },
                    {
                        name: "通江县",
                        id: 2681,
                        upid: 2678
                    },
                    {
                        name: "南江县",
                        id: 2682,
                        upid: 2678
                    },
                    {
                        name: "平昌县",
                        id: 2683,
                        upid: 2678
                    }
                ]
            },
            {
                name: "资阳市",
                id: 2684,
                upid: 2520,
                sub: [
                    {
                        name: "市辖区",
                        id: 2685,
                        upid: 2684
                    },
                    {
                        name: "雁江区",
                        id: 2686,
                        upid: 2684
                    },
                    {
                        name: "安岳县",
                        id: 2687,
                        upid: 2684
                    },
                    {
                        name: "乐至县",
                        id: 2688,
                        upid: 2684
                    },
                    {
                        name: "简阳市",
                        id: 2689,
                        upid: 2684
                    }
                ]
            },
            {
                name: "阿坝藏族羌族自治州",
                id: 2690,
                upid: 2520,
                sub: [
                    {
                        name: "汶川县",
                        id: 2691,
                        upid: 2690
                    },
                    {
                        name: "理县",
                        id: 2692,
                        upid: 2690
                    },
                    {
                        name: "茂县",
                        id: 2693,
                        upid: 2690
                    },
                    {
                        name: "松潘县",
                        id: 2694,
                        upid: 2690
                    },
                    {
                        name: "九寨沟县",
                        id: 2695,
                        upid: 2690
                    },
                    {
                        name: "金川县",
                        id: 2696,
                        upid: 2690
                    },
                    {
                        name: "小金县",
                        id: 2697,
                        upid: 2690
                    },
                    {
                        name: "黑水县",
                        id: 2698,
                        upid: 2690
                    },
                    {
                        name: "马尔康县",
                        id: 2699,
                        upid: 2690
                    },
                    {
                        name: "壤塘县",
                        id: 2700,
                        upid: 2690
                    },
                    {
                        name: "阿坝县",
                        id: 2701,
                        upid: 2690
                    },
                    {
                        name: "若尔盖县",
                        id: 2702,
                        upid: 2690
                    },
                    {
                        name: "红原县",
                        id: 2703,
                        upid: 2690
                    }
                ]
            },
            {
                name: "甘孜藏族自治州",
                id: 2704,
                upid: 2520,
                sub: [
                    {
                        name: "康定县",
                        id: 2705,
                        upid: 2704
                    },
                    {
                        name: "泸定县",
                        id: 2706,
                        upid: 2704
                    },
                    {
                        name: "丹巴县",
                        id: 2707,
                        upid: 2704
                    },
                    {
                        name: "九龙县",
                        id: 2708,
                        upid: 2704
                    },
                    {
                        name: "雅江县",
                        id: 2709,
                        upid: 2704
                    },
                    {
                        name: "道孚县",
                        id: 2710,
                        upid: 2704
                    },
                    {
                        name: "炉霍县",
                        id: 2711,
                        upid: 2704
                    },
                    {
                        name: "甘孜县",
                        id: 2712,
                        upid: 2704
                    },
                    {
                        name: "新龙县",
                        id: 2713,
                        upid: 2704
                    },
                    {
                        name: "德格县",
                        id: 2714,
                        upid: 2704
                    },
                    {
                        name: "白玉县",
                        id: 2715,
                        upid: 2704
                    },
                    {
                        name: "石渠县",
                        id: 2716,
                        upid: 2704
                    },
                    {
                        name: "色达县",
                        id: 2717,
                        upid: 2704
                    },
                    {
                        name: "理塘县",
                        id: 2718,
                        upid: 2704
                    },
                    {
                        name: "巴塘县",
                        id: 2719,
                        upid: 2704
                    },
                    {
                        name: "乡城县",
                        id: 2720,
                        upid: 2704
                    },
                    {
                        name: "稻城县",
                        id: 2721,
                        upid: 2704
                    },
                    {
                        name: "得荣县",
                        id: 2722,
                        upid: 2704
                    }
                ]
            },
            {
                name: "凉山彝族自治州",
                id: 2723,
                upid: 2520,
                sub: [
                    {
                        name: "西昌市",
                        id: 2724,
                        upid: 2723
                    },
                    {
                        name: "木里藏族自治县",
                        id: 2725,
                        upid: 2723
                    },
                    {
                        name: "盐源县",
                        id: 2726,
                        upid: 2723
                    },
                    {
                        name: "德昌县",
                        id: 2727,
                        upid: 2723
                    },
                    {
                        name: "会理县",
                        id: 2728,
                        upid: 2723
                    },
                    {
                        name: "会东县",
                        id: 2729,
                        upid: 2723
                    },
                    {
                        name: "宁南县",
                        id: 2730,
                        upid: 2723
                    },
                    {
                        name: "普格县",
                        id: 2731,
                        upid: 2723
                    },
                    {
                        name: "布拖县",
                        id: 2732,
                        upid: 2723
                    },
                    {
                        name: "金阳县",
                        id: 2733,
                        upid: 2723
                    },
                    {
                        name: "昭觉县",
                        id: 2734,
                        upid: 2723
                    },
                    {
                        name: "喜德县",
                        id: 2735,
                        upid: 2723
                    },
                    {
                        name: "冕宁县",
                        id: 2736,
                        upid: 2723
                    },
                    {
                        name: "越西县",
                        id: 2737,
                        upid: 2723
                    },
                    {
                        name: "甘洛县",
                        id: 2738,
                        upid: 2723
                    },
                    {
                        name: "美姑县",
                        id: 2739,
                        upid: 2723
                    },
                    {
                        name: "雷波县",
                        id: 2740,
                        upid: 2723
                    }
                ]
            }
        ]
    },
    {
        name: "贵州",
        id: 2741,
        upid: 0,
        sub: [
            {
                name: "贵阳市",
                id: 2742,
                upid: 2741,
                sub: [
                    {
                        name: "市辖区",
                        id: 2743,
                        upid: 2742
                    },
                    {
                        name: "南明区",
                        id: 2744,
                        upid: 2742
                    },
                    {
                        name: "云岩区",
                        id: 2745,
                        upid: 2742
                    },
                    {
                        name: "花溪区",
                        id: 2746,
                        upid: 2742
                    },
                    {
                        name: "乌当区",
                        id: 2747,
                        upid: 2742
                    },
                    {
                        name: "白云区",
                        id: 2748,
                        upid: 2742
                    },
                    {
                        name: "小河区",
                        id: 2749,
                        upid: 2742
                    },
                    {
                        name: "开阳县",
                        id: 2750,
                        upid: 2742
                    },
                    {
                        name: "息烽县",
                        id: 2751,
                        upid: 2742
                    },
                    {
                        name: "修文县",
                        id: 2752,
                        upid: 2742
                    },
                    {
                        name: "清镇市",
                        id: 2753,
                        upid: 2742
                    }
                ]
            },
            {
                name: "六盘水市",
                id: 2754,
                upid: 2741,
                sub: [
                    {
                        name: "钟山区",
                        id: 2755,
                        upid: 2754
                    },
                    {
                        name: "六枝特区",
                        id: 2756,
                        upid: 2754
                    },
                    {
                        name: "水城县",
                        id: 2757,
                        upid: 2754
                    },
                    {
                        name: "盘县",
                        id: 2758,
                        upid: 2754
                    }
                ]
            },
            {
                name: "遵义市",
                id: 2759,
                upid: 2741,
                sub: [
                    {
                        name: "市辖区",
                        id: 2760,
                        upid: 2759
                    },
                    {
                        name: "红花岗区",
                        id: 2761,
                        upid: 2759
                    },
                    {
                        name: "汇川区",
                        id: 2762,
                        upid: 2759
                    },
                    {
                        name: "遵义县",
                        id: 2763,
                        upid: 2759
                    },
                    {
                        name: "桐梓县",
                        id: 2764,
                        upid: 2759
                    },
                    {
                        name: "绥阳县",
                        id: 2765,
                        upid: 2759
                    },
                    {
                        name: "正安县",
                        id: 2766,
                        upid: 2759
                    },
                    {
                        name: "道真仡佬族苗族自治县",
                        id: 2767,
                        upid: 2759
                    },
                    {
                        name: "务川仡佬族苗族自治县",
                        id: 2768,
                        upid: 2759
                    },
                    {
                        name: "凤冈县",
                        id: 2769,
                        upid: 2759
                    },
                    {
                        name: "湄潭县",
                        id: 2770,
                        upid: 2759
                    },
                    {
                        name: "余庆县",
                        id: 2771,
                        upid: 2759
                    },
                    {
                        name: "习水县",
                        id: 2772,
                        upid: 2759
                    },
                    {
                        name: "赤水市",
                        id: 2773,
                        upid: 2759
                    },
                    {
                        name: "仁怀市",
                        id: 2774,
                        upid: 2759
                    }
                ]
            },
            {
                name: "安顺市",
                id: 2775,
                upid: 2741,
                sub: [
                    {
                        name: "市辖区",
                        id: 2776,
                        upid: 2775
                    },
                    {
                        name: "西秀区",
                        id: 2777,
                        upid: 2775
                    },
                    {
                        name: "平坝县",
                        id: 2778,
                        upid: 2775
                    },
                    {
                        name: "普定县",
                        id: 2779,
                        upid: 2775
                    },
                    {
                        name: "镇宁布依族苗族自治县",
                        id: 2780,
                        upid: 2775
                    },
                    {
                        name: "关岭布依族苗族自治县",
                        id: 2781,
                        upid: 2775
                    },
                    {
                        name: "紫云苗族布依族自治县",
                        id: 2782,
                        upid: 2775
                    }
                ]
            },
            {
                name: "铜仁地区",
                id: 2783,
                upid: 2741,
                sub: [
                    {
                        name: "铜仁市",
                        id: 2784,
                        upid: 2783
                    },
                    {
                        name: "江口县",
                        id: 2785,
                        upid: 2783
                    },
                    {
                        name: "玉屏侗族自治县",
                        id: 2786,
                        upid: 2783
                    },
                    {
                        name: "石阡县",
                        id: 2787,
                        upid: 2783
                    },
                    {
                        name: "思南县",
                        id: 2788,
                        upid: 2783
                    },
                    {
                        name: "印江土家族苗族自治县",
                        id: 2789,
                        upid: 2783
                    },
                    {
                        name: "德江县",
                        id: 2790,
                        upid: 2783
                    },
                    {
                        name: "沿河土家族自治县",
                        id: 2791,
                        upid: 2783
                    },
                    {
                        name: "松桃苗族自治县",
                        id: 2792,
                        upid: 2783
                    },
                    {
                        name: "万山特区",
                        id: 2793,
                        upid: 2783
                    }
                ]
            },
            {
                name: "黔西南布依族苗族自治州",
                id: 2794,
                upid: 2741,
                sub: [
                    {
                        name: "兴义市",
                        id: 2795,
                        upid: 2794
                    },
                    {
                        name: "兴仁县",
                        id: 2796,
                        upid: 2794
                    },
                    {
                        name: "普安县",
                        id: 2797,
                        upid: 2794
                    },
                    {
                        name: "晴隆县",
                        id: 2798,
                        upid: 2794
                    },
                    {
                        name: "贞丰县",
                        id: 2799,
                        upid: 2794
                    },
                    {
                        name: "望谟县",
                        id: 2800,
                        upid: 2794
                    },
                    {
                        name: "册亨县",
                        id: 2801,
                        upid: 2794
                    },
                    {
                        name: "安龙县",
                        id: 2802,
                        upid: 2794
                    }
                ]
            },
            {
                name: "毕节地区",
                id: 2803,
                upid: 2741,
                sub: [
                    {
                        name: "毕节市",
                        id: 2804,
                        upid: 2803
                    },
                    {
                        name: "大方县",
                        id: 2805,
                        upid: 2803
                    },
                    {
                        name: "黔西县",
                        id: 2806,
                        upid: 2803
                    },
                    {
                        name: "金沙县",
                        id: 2807,
                        upid: 2803
                    },
                    {
                        name: "织金县",
                        id: 2808,
                        upid: 2803
                    },
                    {
                        name: "纳雍县",
                        id: 2809,
                        upid: 2803
                    },
                    {
                        name: "威宁彝族回族苗族自治县",
                        id: 2810,
                        upid: 2803
                    },
                    {
                        name: "赫章县",
                        id: 2811,
                        upid: 2803
                    }
                ]
            },
            {
                name: "黔东南苗族侗族自治州",
                id: 2812,
                upid: 2741,
                sub: [
                    {
                        name: "凯里市",
                        id: 2813,
                        upid: 2812
                    },
                    {
                        name: "黄平县",
                        id: 2814,
                        upid: 2812
                    },
                    {
                        name: "施秉县",
                        id: 2815,
                        upid: 2812
                    },
                    {
                        name: "三穗县",
                        id: 2816,
                        upid: 2812
                    },
                    {
                        name: "镇远县",
                        id: 2817,
                        upid: 2812
                    },
                    {
                        name: "岑巩县",
                        id: 2818,
                        upid: 2812
                    },
                    {
                        name: "天柱县",
                        id: 2819,
                        upid: 2812
                    },
                    {
                        name: "锦屏县",
                        id: 2820,
                        upid: 2812
                    },
                    {
                        name: "剑河县",
                        id: 2821,
                        upid: 2812
                    },
                    {
                        name: "台江县",
                        id: 2822,
                        upid: 2812
                    },
                    {
                        name: "黎平县",
                        id: 2823,
                        upid: 2812
                    },
                    {
                        name: "榕江县",
                        id: 2824,
                        upid: 2812
                    },
                    {
                        name: "从江县",
                        id: 2825,
                        upid: 2812
                    },
                    {
                        name: "雷山县",
                        id: 2826,
                        upid: 2812
                    },
                    {
                        name: "麻江县",
                        id: 2827,
                        upid: 2812
                    },
                    {
                        name: "丹寨县",
                        id: 2828,
                        upid: 2812
                    }
                ]
            },
            {
                name: "黔南布依族苗族自治州",
                id: 2829,
                upid: 2741,
                sub: [
                    {
                        name: "都匀市",
                        id: 2830,
                        upid: 2829
                    },
                    {
                        name: "福泉市",
                        id: 2831,
                        upid: 2829
                    },
                    {
                        name: "荔波县",
                        id: 2832,
                        upid: 2829
                    },
                    {
                        name: "贵定县",
                        id: 2833,
                        upid: 2829
                    },
                    {
                        name: "瓮安县",
                        id: 2834,
                        upid: 2829
                    },
                    {
                        name: "独山县",
                        id: 2835,
                        upid: 2829
                    },
                    {
                        name: "平塘县",
                        id: 2836,
                        upid: 2829
                    },
                    {
                        name: "罗甸县",
                        id: 2837,
                        upid: 2829
                    },
                    {
                        name: "长顺县",
                        id: 2838,
                        upid: 2829
                    },
                    {
                        name: "龙里县",
                        id: 2839,
                        upid: 2829
                    },
                    {
                        name: "惠水县",
                        id: 2840,
                        upid: 2829
                    },
                    {
                        name: "三都水族自治县",
                        id: 2841,
                        upid: 2829
                    }
                ]
            }
        ]
    },
    {
        name: "云南",
        id: 2842,
        upid: 0,
        sub: [
            {
                name: "昆明市",
                id: 2843,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2844,
                        upid: 2843
                    },
                    {
                        name: "五华区",
                        id: 2845,
                        upid: 2843
                    },
                    {
                        name: "盘龙区",
                        id: 2846,
                        upid: 2843
                    },
                    {
                        name: "官渡区",
                        id: 2847,
                        upid: 2843
                    },
                    {
                        name: "西山区",
                        id: 2848,
                        upid: 2843
                    },
                    {
                        name: "东川区",
                        id: 2849,
                        upid: 2843
                    },
                    {
                        name: "呈贡县",
                        id: 2850,
                        upid: 2843
                    },
                    {
                        name: "晋宁县",
                        id: 2851,
                        upid: 2843
                    },
                    {
                        name: "富民县",
                        id: 2852,
                        upid: 2843
                    },
                    {
                        name: "宜良县",
                        id: 2853,
                        upid: 2843
                    },
                    {
                        name: "石林彝族自治县",
                        id: 2854,
                        upid: 2843
                    },
                    {
                        name: "嵩明县",
                        id: 2855,
                        upid: 2843
                    },
                    {
                        name: "禄劝彝族苗族自治县",
                        id: 2856,
                        upid: 2843
                    },
                    {
                        name: "寻甸回族彝族自治县",
                        id: 2857,
                        upid: 2843
                    },
                    {
                        name: "安宁市",
                        id: 2858,
                        upid: 2843
                    }
                ]
            },
            {
                name: "曲靖市",
                id: 2859,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2860,
                        upid: 2859
                    },
                    {
                        name: "麒麟区",
                        id: 2861,
                        upid: 2859
                    },
                    {
                        name: "马龙县",
                        id: 2862,
                        upid: 2859
                    },
                    {
                        name: "陆良县",
                        id: 2863,
                        upid: 2859
                    },
                    {
                        name: "师宗县",
                        id: 2864,
                        upid: 2859
                    },
                    {
                        name: "罗平县",
                        id: 2865,
                        upid: 2859
                    },
                    {
                        name: "富源县",
                        id: 2866,
                        upid: 2859
                    },
                    {
                        name: "会泽县",
                        id: 2867,
                        upid: 2859
                    },
                    {
                        name: "沾益县",
                        id: 2868,
                        upid: 2859
                    },
                    {
                        name: "宣威市",
                        id: 2869,
                        upid: 2859
                    }
                ]
            },
            {
                name: "玉溪市",
                id: 2870,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2871,
                        upid: 2870
                    },
                    {
                        name: "红塔区",
                        id: 2872,
                        upid: 2870
                    },
                    {
                        name: "江川县",
                        id: 2873,
                        upid: 2870
                    },
                    {
                        name: "澄江县",
                        id: 2874,
                        upid: 2870
                    },
                    {
                        name: "通海县",
                        id: 2875,
                        upid: 2870
                    },
                    {
                        name: "华宁县",
                        id: 2876,
                        upid: 2870
                    },
                    {
                        name: "易门县",
                        id: 2877,
                        upid: 2870
                    },
                    {
                        name: "峨山彝族自治县",
                        id: 2878,
                        upid: 2870
                    },
                    {
                        name: "新平彝族傣族自治县",
                        id: 2879,
                        upid: 2870
                    },
                    {
                        name: "元江哈尼族彝族傣族自治县",
                        id: 2880,
                        upid: 2870
                    }
                ]
            },
            {
                name: "保山市",
                id: 2881,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2882,
                        upid: 2881
                    },
                    {
                        name: "隆阳区",
                        id: 2883,
                        upid: 2881
                    },
                    {
                        name: "施甸县",
                        id: 2884,
                        upid: 2881
                    },
                    {
                        name: "腾冲县",
                        id: 2885,
                        upid: 2881
                    },
                    {
                        name: "龙陵县",
                        id: 2886,
                        upid: 2881
                    },
                    {
                        name: "昌宁县",
                        id: 2887,
                        upid: 2881
                    }
                ]
            },
            {
                name: "昭通市",
                id: 2888,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2889,
                        upid: 2888
                    },
                    {
                        name: "昭阳区",
                        id: 2890,
                        upid: 2888
                    },
                    {
                        name: "鲁甸县",
                        id: 2891,
                        upid: 2888
                    },
                    {
                        name: "巧家县",
                        id: 2892,
                        upid: 2888
                    },
                    {
                        name: "盐津县",
                        id: 2893,
                        upid: 2888
                    },
                    {
                        name: "大关县",
                        id: 2894,
                        upid: 2888
                    },
                    {
                        name: "永善县",
                        id: 2895,
                        upid: 2888
                    },
                    {
                        name: "绥江县",
                        id: 2896,
                        upid: 2888
                    },
                    {
                        name: "镇雄县",
                        id: 2897,
                        upid: 2888
                    },
                    {
                        name: "彝良县",
                        id: 2898,
                        upid: 2888
                    },
                    {
                        name: "威信县",
                        id: 2899,
                        upid: 2888
                    },
                    {
                        name: "水富县",
                        id: 2900,
                        upid: 2888
                    }
                ]
            },
            {
                name: "丽江市",
                id: 2901,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2902,
                        upid: 2901
                    },
                    {
                        name: "古城区",
                        id: 2903,
                        upid: 2901
                    },
                    {
                        name: "玉龙纳西族自治县",
                        id: 2904,
                        upid: 2901
                    },
                    {
                        name: "永胜县",
                        id: 2905,
                        upid: 2901
                    },
                    {
                        name: "华坪县",
                        id: 2906,
                        upid: 2901
                    },
                    {
                        name: "宁蒗彝族自治县",
                        id: 2907,
                        upid: 2901
                    }
                ]
            },
            {
                name: "思茅市",
                id: 2908,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2909,
                        upid: 2908
                    },
                    {
                        name: "翠云区",
                        id: 2910,
                        upid: 2908
                    },
                    {
                        name: "普洱哈尼族彝族自治县",
                        id: 2911,
                        upid: 2908
                    },
                    {
                        name: "墨江哈尼族自治县",
                        id: 2912,
                        upid: 2908
                    },
                    {
                        name: "景东彝族自治县",
                        id: 2913,
                        upid: 2908
                    },
                    {
                        name: "景谷傣族彝族自治县",
                        id: 2914,
                        upid: 2908
                    },
                    {
                        name: "镇沅彝族哈尼族拉祜族自治",
                        id: 2915,
                        upid: 2908
                    },
                    {
                        name: "江城哈尼族彝族自治县",
                        id: 2916,
                        upid: 2908
                    },
                    {
                        name: "孟连傣族拉祜族佤族自治县",
                        id: 2917,
                        upid: 2908
                    },
                    {
                        name: "澜沧拉祜族自治县",
                        id: 2918,
                        upid: 2908
                    },
                    {
                        name: "西盟佤族自治县",
                        id: 2919,
                        upid: 2908
                    }
                ]
            },
            {
                name: "临沧市",
                id: 2920,
                upid: 2842,
                sub: [
                    {
                        name: "市辖区",
                        id: 2921,
                        upid: 2920
                    },
                    {
                        name: "临翔区",
                        id: 2922,
                        upid: 2920
                    },
                    {
                        name: "凤庆县",
                        id: 2923,
                        upid: 2920
                    },
                    {
                        name: "云县",
                        id: 2924,
                        upid: 2920
                    },
                    {
                        name: "永德县",
                        id: 2925,
                        upid: 2920
                    },
                    {
                        name: "镇康县",
                        id: 2926,
                        upid: 2920
                    },
                    {
                        name: "双江拉祜族佤族布朗族傣族",
                        id: 2927,
                        upid: 2920
                    },
                    {
                        name: "耿马傣族佤族自治县",
                        id: 2928,
                        upid: 2920
                    },
                    {
                        name: "沧源佤族自治县",
                        id: 2929,
                        upid: 2920
                    }
                ]
            },
            {
                name: "楚雄彝族自治州",
                id: 2930,
                upid: 2842,
                sub: [
                    {
                        name: "楚雄市",
                        id: 2931,
                        upid: 2930
                    },
                    {
                        name: "双柏县",
                        id: 2932,
                        upid: 2930
                    },
                    {
                        name: "牟定县",
                        id: 2933,
                        upid: 2930
                    },
                    {
                        name: "南华县",
                        id: 2934,
                        upid: 2930
                    },
                    {
                        name: "姚安县",
                        id: 2935,
                        upid: 2930
                    },
                    {
                        name: "大姚县",
                        id: 2936,
                        upid: 2930
                    },
                    {
                        name: "永仁县",
                        id: 2937,
                        upid: 2930
                    },
                    {
                        name: "元谋县",
                        id: 2938,
                        upid: 2930
                    },
                    {
                        name: "武定县",
                        id: 2939,
                        upid: 2930
                    },
                    {
                        name: "禄丰县",
                        id: 2940,
                        upid: 2930
                    }
                ]
            },
            {
                name: "红河哈尼族彝族自治州",
                id: 2941,
                upid: 2842,
                sub: [
                    {
                        name: "个旧市",
                        id: 2942,
                        upid: 2941
                    },
                    {
                        name: "开远市",
                        id: 2943,
                        upid: 2941
                    },
                    {
                        name: "蒙自县",
                        id: 2944,
                        upid: 2941
                    },
                    {
                        name: "屏边苗族自治县",
                        id: 2945,
                        upid: 2941
                    },
                    {
                        name: "建水县",
                        id: 2946,
                        upid: 2941
                    },
                    {
                        name: "石屏县",
                        id: 2947,
                        upid: 2941
                    },
                    {
                        name: "弥勒县",
                        id: 2948,
                        upid: 2941
                    },
                    {
                        name: "泸西县",
                        id: 2949,
                        upid: 2941
                    },
                    {
                        name: "元阳县",
                        id: 2950,
                        upid: 2941
                    },
                    {
                        name: "红河县",
                        id: 2951,
                        upid: 2941
                    },
                    {
                        name: "金平苗族瑶族傣族自治县",
                        id: 2952,
                        upid: 2941
                    },
                    {
                        name: "绿春县",
                        id: 2953,
                        upid: 2941
                    },
                    {
                        name: "河口瑶族自治县",
                        id: 2954,
                        upid: 2941
                    }
                ]
            },
            {
                name: "文山壮族苗族自治州",
                id: 2955,
                upid: 2842,
                sub: [
                    {
                        name: "文山县",
                        id: 2956,
                        upid: 2955
                    },
                    {
                        name: "砚山县",
                        id: 2957,
                        upid: 2955
                    },
                    {
                        name: "西畴县",
                        id: 2958,
                        upid: 2955
                    },
                    {
                        name: "麻栗坡县",
                        id: 2959,
                        upid: 2955
                    },
                    {
                        name: "马关县",
                        id: 2960,
                        upid: 2955
                    },
                    {
                        name: "丘北县",
                        id: 2961,
                        upid: 2955
                    },
                    {
                        name: "广南县",
                        id: 2962,
                        upid: 2955
                    },
                    {
                        name: "富宁县",
                        id: 2963,
                        upid: 2955
                    }
                ]
            },
            {
                name: "西双版纳傣族自治州",
                id: 2964,
                upid: 2842,
                sub: [
                    {
                        name: "景洪市",
                        id: 2965,
                        upid: 2964
                    },
                    {
                        name: "勐海县",
                        id: 2966,
                        upid: 2964
                    },
                    {
                        name: "勐腊县",
                        id: 2967,
                        upid: 2964
                    }
                ]
            },
            {
                name: "大理白族自治州",
                id: 2968,
                upid: 2842,
                sub: [
                    {
                        name: "大理市",
                        id: 2969,
                        upid: 2968
                    },
                    {
                        name: "漾濞彝族自治县",
                        id: 2970,
                        upid: 2968
                    },
                    {
                        name: "祥云县",
                        id: 2971,
                        upid: 2968
                    },
                    {
                        name: "宾川县",
                        id: 2972,
                        upid: 2968
                    },
                    {
                        name: "弥渡县",
                        id: 2973,
                        upid: 2968
                    },
                    {
                        name: "南涧彝族自治县",
                        id: 2974,
                        upid: 2968
                    },
                    {
                        name: "巍山彝族回族自治县",
                        id: 2975,
                        upid: 2968
                    },
                    {
                        name: "永平县",
                        id: 2976,
                        upid: 2968
                    },
                    {
                        name: "云龙县",
                        id: 2977,
                        upid: 2968
                    },
                    {
                        name: "洱源县",
                        id: 2978,
                        upid: 2968
                    },
                    {
                        name: "剑川县",
                        id: 2979,
                        upid: 2968
                    },
                    {
                        name: "鹤庆县",
                        id: 2980,
                        upid: 2968
                    }
                ]
            },
            {
                name: "德宏傣族景颇族自治州",
                id: 2981,
                upid: 2842,
                sub: [
                    {
                        name: "瑞丽市",
                        id: 2982,
                        upid: 2981
                    },
                    {
                        name: "潞西市",
                        id: 2983,
                        upid: 2981
                    },
                    {
                        name: "梁河县",
                        id: 2984,
                        upid: 2981
                    },
                    {
                        name: "盈江县",
                        id: 2985,
                        upid: 2981
                    },
                    {
                        name: "陇川县",
                        id: 2986,
                        upid: 2981
                    }
                ]
            },
            {
                name: "怒江傈僳族自治州",
                id: 2987,
                upid: 2842,
                sub: [
                    {
                        name: "泸水县",
                        id: 2988,
                        upid: 2987
                    },
                    {
                        name: "福贡县",
                        id: 2989,
                        upid: 2987
                    },
                    {
                        name: "贡山独龙族怒族自治县",
                        id: 2990,
                        upid: 2987
                    },
                    {
                        name: "兰坪白族普米族自治县",
                        id: 2991,
                        upid: 2987
                    }
                ]
            },
            {
                name: "迪庆藏族自治州",
                id: 2992,
                upid: 2842,
                sub: [
                    {
                        name: "香格里拉县",
                        id: 2993,
                        upid: 2992
                    },
                    {
                        name: "德钦县",
                        id: 2994,
                        upid: 2992
                    },
                    {
                        name: "维西傈僳族自治县",
                        id: 2995,
                        upid: 2992
                    }
                ]
            }
        ]
    },
    {
        name: "西藏",
        id: 2996,
        upid: 0,
        sub: [
            {
                name: "拉萨市",
                id: 2997,
                upid: 2996,
                sub: [
                    {
                        name: "市辖区",
                        id: 2998,
                        upid: 2997
                    },
                    {
                        name: "城关区",
                        id: 2999,
                        upid: 2997
                    },
                    {
                        name: "林周县",
                        id: 3000,
                        upid: 2997
                    },
                    {
                        name: "当雄县",
                        id: 3001,
                        upid: 2997
                    },
                    {
                        name: "尼木县",
                        id: 3002,
                        upid: 2997
                    },
                    {
                        name: "曲水县",
                        id: 3003,
                        upid: 2997
                    },
                    {
                        name: "堆龙德庆县",
                        id: 3004,
                        upid: 2997
                    },
                    {
                        name: "达孜县",
                        id: 3005,
                        upid: 2997
                    },
                    {
                        name: "墨竹工卡县",
                        id: 3006,
                        upid: 2997
                    }
                ]
            },
            {
                name: "昌都地区",
                id: 3007,
                upid: 2996,
                sub: [
                    {
                        name: "昌都县",
                        id: 3008,
                        upid: 3007
                    },
                    {
                        name: "江达县",
                        id: 3009,
                        upid: 3007
                    },
                    {
                        name: "贡觉县",
                        id: 3010,
                        upid: 3007
                    },
                    {
                        name: "类乌齐县",
                        id: 3011,
                        upid: 3007
                    },
                    {
                        name: "丁青县",
                        id: 3012,
                        upid: 3007
                    },
                    {
                        name: "察雅县",
                        id: 3013,
                        upid: 3007
                    },
                    {
                        name: "八宿县",
                        id: 3014,
                        upid: 3007
                    },
                    {
                        name: "左贡县",
                        id: 3015,
                        upid: 3007
                    },
                    {
                        name: "芒康县",
                        id: 3016,
                        upid: 3007
                    },
                    {
                        name: "洛隆县",
                        id: 3017,
                        upid: 3007
                    },
                    {
                        name: "边坝县",
                        id: 3018,
                        upid: 3007
                    }
                ]
            },
            {
                name: "山南地区",
                id: 3019,
                upid: 2996,
                sub: [
                    {
                        name: "乃东县",
                        id: 3020,
                        upid: 3019
                    },
                    {
                        name: "扎囊县",
                        id: 3021,
                        upid: 3019
                    },
                    {
                        name: "贡嘎县",
                        id: 3022,
                        upid: 3019
                    },
                    {
                        name: "桑日县",
                        id: 3023,
                        upid: 3019
                    },
                    {
                        name: "琼结县",
                        id: 3024,
                        upid: 3019
                    },
                    {
                        name: "曲松县",
                        id: 3025,
                        upid: 3019
                    },
                    {
                        name: "措美县",
                        id: 3026,
                        upid: 3019
                    },
                    {
                        name: "洛扎县",
                        id: 3027,
                        upid: 3019
                    },
                    {
                        name: "加查县",
                        id: 3028,
                        upid: 3019
                    },
                    {
                        name: "隆子县",
                        id: 3029,
                        upid: 3019
                    },
                    {
                        name: "错那县",
                        id: 3030,
                        upid: 3019
                    },
                    {
                        name: "浪卡子县",
                        id: 3031,
                        upid: 3019
                    }
                ]
            },
            {
                name: "日喀则地区",
                id: 3032,
                upid: 2996,
                sub: [
                    {
                        name: "日喀则市",
                        id: 3033,
                        upid: 3032
                    },
                    {
                        name: "南木林县",
                        id: 3034,
                        upid: 3032
                    },
                    {
                        name: "江孜县",
                        id: 3035,
                        upid: 3032
                    },
                    {
                        name: "定日县",
                        id: 3036,
                        upid: 3032
                    },
                    {
                        name: "萨迦县",
                        id: 3037,
                        upid: 3032
                    },
                    {
                        name: "拉孜县",
                        id: 3038,
                        upid: 3032
                    },
                    {
                        name: "昂仁县",
                        id: 3039,
                        upid: 3032
                    },
                    {
                        name: "谢通门县",
                        id: 3040,
                        upid: 3032
                    },
                    {
                        name: "白朗县",
                        id: 3041,
                        upid: 3032
                    },
                    {
                        name: "仁布县",
                        id: 3042,
                        upid: 3032
                    },
                    {
                        name: "康马县",
                        id: 3043,
                        upid: 3032
                    },
                    {
                        name: "定结县",
                        id: 3044,
                        upid: 3032
                    },
                    {
                        name: "仲巴县",
                        id: 3045,
                        upid: 3032
                    },
                    {
                        name: "亚东县",
                        id: 3046,
                        upid: 3032
                    },
                    {
                        name: "吉隆县",
                        id: 3047,
                        upid: 3032
                    },
                    {
                        name: "聂拉木县",
                        id: 3048,
                        upid: 3032
                    },
                    {
                        name: "萨嘎县",
                        id: 3049,
                        upid: 3032
                    },
                    {
                        name: "岗巴县",
                        id: 3050,
                        upid: 3032
                    }
                ]
            },
            {
                name: "那曲地区",
                id: 3051,
                upid: 2996,
                sub: [
                    {
                        name: "那曲县",
                        id: 3052,
                        upid: 3051
                    },
                    {
                        name: "嘉黎县",
                        id: 3053,
                        upid: 3051
                    },
                    {
                        name: "比如县",
                        id: 3054,
                        upid: 3051
                    },
                    {
                        name: "聂荣县",
                        id: 3055,
                        upid: 3051
                    },
                    {
                        name: "安多县",
                        id: 3056,
                        upid: 3051
                    },
                    {
                        name: "申扎县",
                        id: 3057,
                        upid: 3051
                    },
                    {
                        name: "索县",
                        id: 3058,
                        upid: 3051
                    },
                    {
                        name: "班戈县",
                        id: 3059,
                        upid: 3051
                    },
                    {
                        name: "巴青县",
                        id: 3060,
                        upid: 3051
                    },
                    {
                        name: "尼玛县",
                        id: 3061,
                        upid: 3051
                    }
                ]
            },
            {
                name: "阿里地区",
                id: 3062,
                upid: 2996,
                sub: [
                    {
                        name: "普兰县",
                        id: 3063,
                        upid: 3062
                    },
                    {
                        name: "札达县",
                        id: 3064,
                        upid: 3062
                    },
                    {
                        name: "噶尔县",
                        id: 3065,
                        upid: 3062
                    },
                    {
                        name: "日土县",
                        id: 3066,
                        upid: 3062
                    },
                    {
                        name: "革吉县",
                        id: 3067,
                        upid: 3062
                    },
                    {
                        name: "改则县",
                        id: 3068,
                        upid: 3062
                    },
                    {
                        name: "措勤县",
                        id: 3069,
                        upid: 3062
                    }
                ]
            },
            {
                name: "林芝地区",
                id: 3070,
                upid: 2996,
                sub: [
                    {
                        name: "林芝县",
                        id: 3071,
                        upid: 3070
                    },
                    {
                        name: "工布江达县",
                        id: 3072,
                        upid: 3070
                    },
                    {
                        name: "米林县",
                        id: 3073,
                        upid: 3070
                    },
                    {
                        name: "墨脱县",
                        id: 3074,
                        upid: 3070
                    },
                    {
                        name: "波密县",
                        id: 3075,
                        upid: 3070
                    },
                    {
                        name: "察隅县",
                        id: 3076,
                        upid: 3070
                    },
                    {
                        name: "朗县",
                        id: 3077,
                        upid: 3070
                    }
                ]
            }
        ]
    },
    {
        name: "陕西",
        id: 3078,
        upid: 0,
        sub: [
            {
                name: "西安市",
                id: 3079,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3080,
                        upid: 3079
                    },
                    {
                        name: "新城区",
                        id: 3081,
                        upid: 3079
                    },
                    {
                        name: "碑林区",
                        id: 3082,
                        upid: 3079
                    },
                    {
                        name: "莲湖区",
                        id: 3083,
                        upid: 3079
                    },
                    {
                        name: "灞桥区",
                        id: 3084,
                        upid: 3079
                    },
                    {
                        name: "未央区",
                        id: 3085,
                        upid: 3079
                    },
                    {
                        name: "雁塔区",
                        id: 3086,
                        upid: 3079
                    },
                    {
                        name: "阎良区",
                        id: 3087,
                        upid: 3079
                    },
                    {
                        name: "临潼区",
                        id: 3088,
                        upid: 3079
                    },
                    {
                        name: "长安区",
                        id: 3089,
                        upid: 3079
                    },
                    {
                        name: "蓝田县",
                        id: 3090,
                        upid: 3079
                    },
                    {
                        name: "周至县",
                        id: 3091,
                        upid: 3079
                    },
                    {
                        name: "户县",
                        id: 3092,
                        upid: 3079
                    },
                    {
                        name: "高陵县",
                        id: 3093,
                        upid: 3079
                    }
                ]
            },
            {
                name: "铜川市",
                id: 3094,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3095,
                        upid: 3094
                    },
                    {
                        name: "王益区",
                        id: 3096,
                        upid: 3094
                    },
                    {
                        name: "印台区",
                        id: 3097,
                        upid: 3094
                    },
                    {
                        name: "耀州区",
                        id: 3098,
                        upid: 3094
                    },
                    {
                        name: "宜君县",
                        id: 3099,
                        upid: 3094
                    }
                ]
            },
            {
                name: "宝鸡市",
                id: 3100,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3101,
                        upid: 3100
                    },
                    {
                        name: "渭滨区",
                        id: 3102,
                        upid: 3100
                    },
                    {
                        name: "金台区",
                        id: 3103,
                        upid: 3100
                    },
                    {
                        name: "陈仓区",
                        id: 3104,
                        upid: 3100
                    },
                    {
                        name: "凤翔县",
                        id: 3105,
                        upid: 3100
                    },
                    {
                        name: "岐山县",
                        id: 3106,
                        upid: 3100
                    },
                    {
                        name: "扶风县",
                        id: 3107,
                        upid: 3100
                    },
                    {
                        name: "眉县",
                        id: 3108,
                        upid: 3100
                    },
                    {
                        name: "陇县",
                        id: 3109,
                        upid: 3100
                    },
                    {
                        name: "千阳县",
                        id: 3110,
                        upid: 3100
                    },
                    {
                        name: "麟游县",
                        id: 3111,
                        upid: 3100
                    },
                    {
                        name: "凤县",
                        id: 3112,
                        upid: 3100
                    },
                    {
                        name: "太白县",
                        id: 3113,
                        upid: 3100
                    }
                ]
            },
            {
                name: "咸阳市",
                id: 3114,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3115,
                        upid: 3114
                    },
                    {
                        name: "秦都区",
                        id: 3116,
                        upid: 3114
                    },
                    {
                        name: "杨凌区",
                        id: 3117,
                        upid: 3114
                    },
                    {
                        name: "渭城区",
                        id: 3118,
                        upid: 3114
                    },
                    {
                        name: "三原县",
                        id: 3119,
                        upid: 3114
                    },
                    {
                        name: "泾阳县",
                        id: 3120,
                        upid: 3114
                    },
                    {
                        name: "乾县",
                        id: 3121,
                        upid: 3114
                    },
                    {
                        name: "礼泉县",
                        id: 3122,
                        upid: 3114
                    },
                    {
                        name: "永寿县",
                        id: 3123,
                        upid: 3114
                    },
                    {
                        name: "彬县",
                        id: 3124,
                        upid: 3114
                    },
                    {
                        name: "长武县",
                        id: 3125,
                        upid: 3114
                    },
                    {
                        name: "旬邑县",
                        id: 3126,
                        upid: 3114
                    },
                    {
                        name: "淳化县",
                        id: 3127,
                        upid: 3114
                    },
                    {
                        name: "武功县",
                        id: 3128,
                        upid: 3114
                    },
                    {
                        name: "兴平市",
                        id: 3129,
                        upid: 3114
                    }
                ]
            },
            {
                name: "渭南市",
                id: 3130,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3131,
                        upid: 3130
                    },
                    {
                        name: "临渭区",
                        id: 3132,
                        upid: 3130
                    },
                    {
                        name: "华县",
                        id: 3133,
                        upid: 3130
                    },
                    {
                        name: "潼关县",
                        id: 3134,
                        upid: 3130
                    },
                    {
                        name: "大荔县",
                        id: 3135,
                        upid: 3130
                    },
                    {
                        name: "合阳县",
                        id: 3136,
                        upid: 3130
                    },
                    {
                        name: "澄城县",
                        id: 3137,
                        upid: 3130
                    },
                    {
                        name: "蒲城县",
                        id: 3138,
                        upid: 3130
                    },
                    {
                        name: "白水县",
                        id: 3139,
                        upid: 3130
                    },
                    {
                        name: "富平县",
                        id: 3140,
                        upid: 3130
                    },
                    {
                        name: "韩城市",
                        id: 3141,
                        upid: 3130
                    },
                    {
                        name: "华阴市",
                        id: 3142,
                        upid: 3130
                    }
                ]
            },
            {
                name: "延安市",
                id: 3143,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3144,
                        upid: 3143
                    },
                    {
                        name: "宝塔区",
                        id: 3145,
                        upid: 3143
                    },
                    {
                        name: "延长县",
                        id: 3146,
                        upid: 3143
                    },
                    {
                        name: "延川县",
                        id: 3147,
                        upid: 3143
                    },
                    {
                        name: "子长县",
                        id: 3148,
                        upid: 3143
                    },
                    {
                        name: "安塞县",
                        id: 3149,
                        upid: 3143
                    },
                    {
                        name: "志丹县",
                        id: 3150,
                        upid: 3143
                    },
                    {
                        name: "吴旗县",
                        id: 3151,
                        upid: 3143
                    },
                    {
                        name: "甘泉县",
                        id: 3152,
                        upid: 3143
                    },
                    {
                        name: "富县",
                        id: 3153,
                        upid: 3143
                    },
                    {
                        name: "洛川县",
                        id: 3154,
                        upid: 3143
                    },
                    {
                        name: "宜川县",
                        id: 3155,
                        upid: 3143
                    },
                    {
                        name: "黄龙县",
                        id: 3156,
                        upid: 3143
                    },
                    {
                        name: "黄陵县",
                        id: 3157,
                        upid: 3143
                    }
                ]
            },
            {
                name: "汉中市",
                id: 3158,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3159,
                        upid: 3158
                    },
                    {
                        name: "汉台区",
                        id: 3160,
                        upid: 3158
                    },
                    {
                        name: "南郑县",
                        id: 3161,
                        upid: 3158
                    },
                    {
                        name: "城固县",
                        id: 3162,
                        upid: 3158
                    },
                    {
                        name: "洋县",
                        id: 3163,
                        upid: 3158
                    },
                    {
                        name: "西乡县",
                        id: 3164,
                        upid: 3158
                    },
                    {
                        name: "勉县",
                        id: 3165,
                        upid: 3158
                    },
                    {
                        name: "宁强县",
                        id: 3166,
                        upid: 3158
                    },
                    {
                        name: "略阳县",
                        id: 3167,
                        upid: 3158
                    },
                    {
                        name: "镇巴县",
                        id: 3168,
                        upid: 3158
                    },
                    {
                        name: "留坝县",
                        id: 3169,
                        upid: 3158
                    },
                    {
                        name: "佛坪县",
                        id: 3170,
                        upid: 3158
                    }
                ]
            },
            {
                name: "榆林市",
                id: 3171,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3172,
                        upid: 3171
                    },
                    {
                        name: "榆阳区",
                        id: 3173,
                        upid: 3171
                    },
                    {
                        name: "神木县",
                        id: 3174,
                        upid: 3171
                    },
                    {
                        name: "府谷县",
                        id: 3175,
                        upid: 3171
                    },
                    {
                        name: "横山县",
                        id: 3176,
                        upid: 3171
                    },
                    {
                        name: "靖边县",
                        id: 3177,
                        upid: 3171
                    },
                    {
                        name: "定边县",
                        id: 3178,
                        upid: 3171
                    },
                    {
                        name: "绥德县",
                        id: 3179,
                        upid: 3171
                    },
                    {
                        name: "米脂县",
                        id: 3180,
                        upid: 3171
                    },
                    {
                        name: "佳县",
                        id: 3181,
                        upid: 3171
                    },
                    {
                        name: "吴堡县",
                        id: 3182,
                        upid: 3171
                    },
                    {
                        name: "清涧县",
                        id: 3183,
                        upid: 3171
                    },
                    {
                        name: "子洲县",
                        id: 3184,
                        upid: 3171
                    }
                ]
            },
            {
                name: "安康市",
                id: 3185,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3186,
                        upid: 3185
                    },
                    {
                        name: "汉滨区",
                        id: 3187,
                        upid: 3185
                    },
                    {
                        name: "汉阴县",
                        id: 3188,
                        upid: 3185
                    },
                    {
                        name: "石泉县",
                        id: 3189,
                        upid: 3185
                    },
                    {
                        name: "宁陕县",
                        id: 3190,
                        upid: 3185
                    },
                    {
                        name: "紫阳县",
                        id: 3191,
                        upid: 3185
                    },
                    {
                        name: "岚皋县",
                        id: 3192,
                        upid: 3185
                    },
                    {
                        name: "平利县",
                        id: 3193,
                        upid: 3185
                    },
                    {
                        name: "镇坪县",
                        id: 3194,
                        upid: 3185
                    },
                    {
                        name: "旬阳县",
                        id: 3195,
                        upid: 3185
                    },
                    {
                        name: "白河县",
                        id: 3196,
                        upid: 3185
                    }
                ]
            },
            {
                name: "商洛市",
                id: 3197,
                upid: 3078,
                sub: [
                    {
                        name: "市辖区",
                        id: 3198,
                        upid: 3197
                    },
                    {
                        name: "商州区",
                        id: 3199,
                        upid: 3197
                    },
                    {
                        name: "洛南县",
                        id: 3200,
                        upid: 3197
                    },
                    {
                        name: "丹凤县",
                        id: 3201,
                        upid: 3197
                    },
                    {
                        name: "商南县",
                        id: 3202,
                        upid: 3197
                    },
                    {
                        name: "山阳县",
                        id: 3203,
                        upid: 3197
                    },
                    {
                        name: "镇安县",
                        id: 3204,
                        upid: 3197
                    },
                    {
                        name: "柞水县",
                        id: 3205,
                        upid: 3197
                    }
                ]
            }
        ]
    },
    {
        name: "甘肃",
        id: 3206,
        upid: 0,
        sub: [
            {
                name: "兰州市",
                id: 3207,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3208,
                        upid: 3207
                    },
                    {
                        name: "城关区",
                        id: 3209,
                        upid: 3207
                    },
                    {
                        name: "七里河区",
                        id: 3210,
                        upid: 3207
                    },
                    {
                        name: "西固区",
                        id: 3211,
                        upid: 3207
                    },
                    {
                        name: "安宁区",
                        id: 3212,
                        upid: 3207
                    },
                    {
                        name: "红古区",
                        id: 3213,
                        upid: 3207
                    },
                    {
                        name: "永登县",
                        id: 3214,
                        upid: 3207
                    },
                    {
                        name: "皋兰县",
                        id: 3215,
                        upid: 3207
                    },
                    {
                        name: "榆中县",
                        id: 3216,
                        upid: 3207
                    }
                ]
            },
            {
                name: "嘉峪关市",
                id: 3217,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3218,
                        upid: 3217
                    }
                ]
            },
            {
                name: "金昌市",
                id: 3219,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3220,
                        upid: 3219
                    },
                    {
                        name: "金川区",
                        id: 3221,
                        upid: 3219
                    },
                    {
                        name: "永昌县",
                        id: 3222,
                        upid: 3219
                    }
                ]
            },
            {
                name: "白银市",
                id: 3223,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3224,
                        upid: 3223
                    },
                    {
                        name: "白银区",
                        id: 3225,
                        upid: 3223
                    },
                    {
                        name: "平川区",
                        id: 3226,
                        upid: 3223
                    },
                    {
                        name: "靖远县",
                        id: 3227,
                        upid: 3223
                    },
                    {
                        name: "会宁县",
                        id: 3228,
                        upid: 3223
                    },
                    {
                        name: "景泰县",
                        id: 3229,
                        upid: 3223
                    }
                ]
            },
            {
                name: "天水市",
                id: 3230,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3231,
                        upid: 3230
                    },
                    {
                        name: "秦城区",
                        id: 3232,
                        upid: 3230
                    },
                    {
                        name: "北道区",
                        id: 3233,
                        upid: 3230
                    },
                    {
                        name: "清水县",
                        id: 3234,
                        upid: 3230
                    },
                    {
                        name: "秦安县",
                        id: 3235,
                        upid: 3230
                    },
                    {
                        name: "甘谷县",
                        id: 3236,
                        upid: 3230
                    },
                    {
                        name: "武山县",
                        id: 3237,
                        upid: 3230
                    },
                    {
                        name: "张家川回族自治县",
                        id: 3238,
                        upid: 3230
                    }
                ]
            },
            {
                name: "武威市",
                id: 3239,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3240,
                        upid: 3239
                    },
                    {
                        name: "凉州区",
                        id: 3241,
                        upid: 3239
                    },
                    {
                        name: "民勤县",
                        id: 3242,
                        upid: 3239
                    },
                    {
                        name: "古浪县",
                        id: 3243,
                        upid: 3239
                    },
                    {
                        name: "天祝藏族自治县",
                        id: 3244,
                        upid: 3239
                    }
                ]
            },
            {
                name: "张掖市",
                id: 3245,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3246,
                        upid: 3245
                    },
                    {
                        name: "甘州区",
                        id: 3247,
                        upid: 3245
                    },
                    {
                        name: "肃南裕固族自治县",
                        id: 3248,
                        upid: 3245
                    },
                    {
                        name: "民乐县",
                        id: 3249,
                        upid: 3245
                    },
                    {
                        name: "临泽县",
                        id: 3250,
                        upid: 3245
                    },
                    {
                        name: "高台县",
                        id: 3251,
                        upid: 3245
                    },
                    {
                        name: "山丹县",
                        id: 3252,
                        upid: 3245
                    }
                ]
            },
            {
                name: "平凉市",
                id: 3253,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3254,
                        upid: 3253
                    },
                    {
                        name: "崆峒区",
                        id: 3255,
                        upid: 3253
                    },
                    {
                        name: "泾川县",
                        id: 3256,
                        upid: 3253
                    },
                    {
                        name: "灵台县",
                        id: 3257,
                        upid: 3253
                    },
                    {
                        name: "崇信县",
                        id: 3258,
                        upid: 3253
                    },
                    {
                        name: "华亭县",
                        id: 3259,
                        upid: 3253
                    },
                    {
                        name: "庄浪县",
                        id: 3260,
                        upid: 3253
                    },
                    {
                        name: "静宁县",
                        id: 3261,
                        upid: 3253
                    }
                ]
            },
            {
                name: "酒泉市",
                id: 3262,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3263,
                        upid: 3262
                    },
                    {
                        name: "肃州区",
                        id: 3264,
                        upid: 3262
                    },
                    {
                        name: "金塔县",
                        id: 3265,
                        upid: 3262
                    },
                    {
                        name: "安西县",
                        id: 3266,
                        upid: 3262
                    },
                    {
                        name: "肃北蒙古族自治县",
                        id: 3267,
                        upid: 3262
                    },
                    {
                        name: "阿克塞哈萨克族自治县",
                        id: 3268,
                        upid: 3262
                    },
                    {
                        name: "玉门市",
                        id: 3269,
                        upid: 3262
                    },
                    {
                        name: "敦煌市",
                        id: 3270,
                        upid: 3262
                    }
                ]
            },
            {
                name: "庆阳市",
                id: 3271,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3272,
                        upid: 3271
                    },
                    {
                        name: "西峰区",
                        id: 3273,
                        upid: 3271
                    },
                    {
                        name: "庆城县",
                        id: 3274,
                        upid: 3271
                    },
                    {
                        name: "环县",
                        id: 3275,
                        upid: 3271
                    },
                    {
                        name: "华池县",
                        id: 3276,
                        upid: 3271
                    },
                    {
                        name: "合水县",
                        id: 3277,
                        upid: 3271
                    },
                    {
                        name: "正宁县",
                        id: 3278,
                        upid: 3271
                    },
                    {
                        name: "宁县",
                        id: 3279,
                        upid: 3271
                    },
                    {
                        name: "镇原县",
                        id: 3280,
                        upid: 3271
                    }
                ]
            },
            {
                name: "定西市",
                id: 3281,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3282,
                        upid: 3281
                    },
                    {
                        name: "安定区",
                        id: 3283,
                        upid: 3281
                    },
                    {
                        name: "通渭县",
                        id: 3284,
                        upid: 3281
                    },
                    {
                        name: "陇西县",
                        id: 3285,
                        upid: 3281
                    },
                    {
                        name: "渭源县",
                        id: 3286,
                        upid: 3281
                    },
                    {
                        name: "临洮县",
                        id: 3287,
                        upid: 3281
                    },
                    {
                        name: "漳县",
                        id: 3288,
                        upid: 3281
                    },
                    {
                        name: "岷县",
                        id: 3289,
                        upid: 3281
                    }
                ]
            },
            {
                name: "陇南市",
                id: 3290,
                upid: 3206,
                sub: [
                    {
                        name: "市辖区",
                        id: 3291,
                        upid: 3290
                    },
                    {
                        name: "武都区",
                        id: 3292,
                        upid: 3290
                    },
                    {
                        name: "成县",
                        id: 3293,
                        upid: 3290
                    },
                    {
                        name: "文县",
                        id: 3294,
                        upid: 3290
                    },
                    {
                        name: "宕昌县",
                        id: 3295,
                        upid: 3290
                    },
                    {
                        name: "康县",
                        id: 3296,
                        upid: 3290
                    },
                    {
                        name: "西和县",
                        id: 3297,
                        upid: 3290
                    },
                    {
                        name: "礼县",
                        id: 3298,
                        upid: 3290
                    },
                    {
                        name: "徽县",
                        id: 3299,
                        upid: 3290
                    },
                    {
                        name: "两当县",
                        id: 3300,
                        upid: 3290
                    }
                ]
            },
            {
                name: "临夏回族自治州",
                id: 3301,
                upid: 3206,
                sub: [
                    {
                        name: "临夏市",
                        id: 3302,
                        upid: 3301
                    },
                    {
                        name: "临夏县",
                        id: 3303,
                        upid: 3301
                    },
                    {
                        name: "康乐县",
                        id: 3304,
                        upid: 3301
                    },
                    {
                        name: "永靖县",
                        id: 3305,
                        upid: 3301
                    },
                    {
                        name: "广河县",
                        id: 3306,
                        upid: 3301
                    },
                    {
                        name: "和政县",
                        id: 3307,
                        upid: 3301
                    },
                    {
                        name: "东乡族自治县",
                        id: 3308,
                        upid: 3301
                    },
                    {
                        name: "积石山保安族东乡族撒拉族",
                        id: 3309,
                        upid: 3301
                    }
                ]
            },
            {
                name: "甘南藏族自治州",
                id: 3310,
                upid: 3206,
                sub: [
                    {
                        name: "合作市",
                        id: 3311,
                        upid: 3310
                    },
                    {
                        name: "临潭县",
                        id: 3312,
                        upid: 3310
                    },
                    {
                        name: "卓尼县",
                        id: 3313,
                        upid: 3310
                    },
                    {
                        name: "舟曲县",
                        id: 3314,
                        upid: 3310
                    },
                    {
                        name: "迭部县",
                        id: 3315,
                        upid: 3310
                    },
                    {
                        name: "玛曲县",
                        id: 3316,
                        upid: 3310
                    },
                    {
                        name: "碌曲县",
                        id: 3317,
                        upid: 3310
                    },
                    {
                        name: "夏河县",
                        id: 3318,
                        upid: 3310
                    }
                ]
            }
        ]
    },
    {
        name: "青海",
        id: 3319,
        upid: 0,
        sub: [
            {
                name: "西宁市",
                id: 3320,
                upid: 3319,
                sub: [
                    {
                        name: "市辖区",
                        id: 3321,
                        upid: 3320
                    },
                    {
                        name: "城东区",
                        id: 3322,
                        upid: 3320
                    },
                    {
                        name: "城中区",
                        id: 3323,
                        upid: 3320
                    },
                    {
                        name: "城西区",
                        id: 3324,
                        upid: 3320
                    },
                    {
                        name: "城北区",
                        id: 3325,
                        upid: 3320
                    },
                    {
                        name: "大通回族土族自治县",
                        id: 3326,
                        upid: 3320
                    },
                    {
                        name: "湟中县",
                        id: 3327,
                        upid: 3320
                    },
                    {
                        name: "湟源县",
                        id: 3328,
                        upid: 3320
                    }
                ]
            },
            {
                name: "海东地区",
                id: 3329,
                upid: 3319,
                sub: [
                    {
                        name: "平安县",
                        id: 3330,
                        upid: 3329
                    },
                    {
                        name: "民和回族土族自治县",
                        id: 3331,
                        upid: 3329
                    },
                    {
                        name: "乐都县",
                        id: 3332,
                        upid: 3329
                    },
                    {
                        name: "互助土族自治县",
                        id: 3333,
                        upid: 3329
                    },
                    {
                        name: "化隆回族自治县",
                        id: 3334,
                        upid: 3329
                    },
                    {
                        name: "循化撒拉族自治县",
                        id: 3335,
                        upid: 3329
                    }
                ]
            },
            {
                name: "海北藏族自治州",
                id: 3336,
                upid: 3319,
                sub: [
                    {
                        name: "门源回族自治县",
                        id: 3337,
                        upid: 3336
                    },
                    {
                        name: "祁连县",
                        id: 3338,
                        upid: 3336
                    },
                    {
                        name: "海晏县",
                        id: 3339,
                        upid: 3336
                    },
                    {
                        name: "刚察县",
                        id: 3340,
                        upid: 3336
                    }
                ]
            },
            {
                name: "黄南藏族自治州",
                id: 3341,
                upid: 3319,
                sub: [
                    {
                        name: "同仁县",
                        id: 3342,
                        upid: 3341
                    },
                    {
                        name: "尖扎县",
                        id: 3343,
                        upid: 3341
                    },
                    {
                        name: "泽库县",
                        id: 3344,
                        upid: 3341
                    },
                    {
                        name: "河南蒙古族自治县",
                        id: 3345,
                        upid: 3341
                    }
                ]
            },
            {
                name: "海南藏族自治州",
                id: 3346,
                upid: 3319,
                sub: [
                    {
                        name: "共和县",
                        id: 3347,
                        upid: 3346
                    },
                    {
                        name: "同德县",
                        id: 3348,
                        upid: 3346
                    },
                    {
                        name: "贵德县",
                        id: 3349,
                        upid: 3346
                    },
                    {
                        name: "兴海县",
                        id: 3350,
                        upid: 3346
                    },
                    {
                        name: "贵南县",
                        id: 3351,
                        upid: 3346
                    }
                ]
            },
            {
                name: "果洛藏族自治州",
                id: 3352,
                upid: 3319,
                sub: [
                    {
                        name: "玛沁县",
                        id: 3353,
                        upid: 3352
                    },
                    {
                        name: "班玛县",
                        id: 3354,
                        upid: 3352
                    },
                    {
                        name: "甘德县",
                        id: 3355,
                        upid: 3352
                    },
                    {
                        name: "达日县",
                        id: 3356,
                        upid: 3352
                    },
                    {
                        name: "久治县",
                        id: 3357,
                        upid: 3352
                    },
                    {
                        name: "玛多县",
                        id: 3358,
                        upid: 3352
                    }
                ]
            },
            {
                name: "玉树藏族自治州",
                id: 3359,
                upid: 3319,
                sub: [
                    {
                        name: "玉树县",
                        id: 3360,
                        upid: 3359
                    },
                    {
                        name: "杂多县",
                        id: 3361,
                        upid: 3359
                    },
                    {
                        name: "称多县",
                        id: 3362,
                        upid: 3359
                    },
                    {
                        name: "治多县",
                        id: 3363,
                        upid: 3359
                    },
                    {
                        name: "囊谦县",
                        id: 3364,
                        upid: 3359
                    },
                    {
                        name: "曲麻莱县",
                        id: 3365,
                        upid: 3359
                    }
                ]
            },
            {
                name: "海西蒙古族藏族自治州",
                id: 3366,
                upid: 3319,
                sub: [
                    {
                        name: "格尔木市",
                        id: 3367,
                        upid: 3366
                    },
                    {
                        name: "德令哈市",
                        id: 3368,
                        upid: 3366
                    },
                    {
                        name: "乌兰县",
                        id: 3369,
                        upid: 3366
                    },
                    {
                        name: "都兰县",
                        id: 3370,
                        upid: 3366
                    },
                    {
                        name: "天峻县",
                        id: 3371,
                        upid: 3366
                    }
                ]
            }
        ]
    },
    {
        name: "宁夏",
        id: 3372,
        upid: 0,
        sub: [
            {
                name: "银川市",
                id: 3373,
                upid: 3372,
                sub: [
                    {
                        name: "市辖区",
                        id: 3374,
                        upid: 3373
                    },
                    {
                        name: "兴庆区",
                        id: 3375,
                        upid: 3373
                    },
                    {
                        name: "西夏区",
                        id: 3376,
                        upid: 3373
                    },
                    {
                        name: "金凤区",
                        id: 3377,
                        upid: 3373
                    },
                    {
                        name: "永宁县",
                        id: 3378,
                        upid: 3373
                    },
                    {
                        name: "贺兰县",
                        id: 3379,
                        upid: 3373
                    },
                    {
                        name: "灵武市",
                        id: 3380,
                        upid: 3373
                    }
                ]
            },
            {
                name: "石嘴山市",
                id: 3381,
                upid: 3372,
                sub: [
                    {
                        name: "市辖区",
                        id: 3382,
                        upid: 3381
                    },
                    {
                        name: "大武口区",
                        id: 3383,
                        upid: 3381
                    },
                    {
                        name: "惠农区",
                        id: 3384,
                        upid: 3381
                    },
                    {
                        name: "平罗县",
                        id: 3385,
                        upid: 3381
                    }
                ]
            },
            {
                name: "吴忠市",
                id: 3386,
                upid: 3372,
                sub: [
                    {
                        name: "市辖区",
                        id: 3387,
                        upid: 3386
                    },
                    {
                        name: "利通区",
                        id: 3388,
                        upid: 3386
                    },
                    {
                        name: "盐池县",
                        id: 3389,
                        upid: 3386
                    },
                    {
                        name: "同心县",
                        id: 3390,
                        upid: 3386
                    },
                    {
                        name: "青铜峡市",
                        id: 3391,
                        upid: 3386
                    }
                ]
            },
            {
                name: "固原市",
                id: 3392,
                upid: 3372,
                sub: [
                    {
                        name: "市辖区",
                        id: 3393,
                        upid: 3392
                    },
                    {
                        name: "原州区",
                        id: 3394,
                        upid: 3392
                    },
                    {
                        name: "西吉县",
                        id: 3395,
                        upid: 3392
                    },
                    {
                        name: "隆德县",
                        id: 3396,
                        upid: 3392
                    },
                    {
                        name: "泾源县",
                        id: 3397,
                        upid: 3392
                    },
                    {
                        name: "彭阳县",
                        id: 3398,
                        upid: 3392
                    }
                ]
            },
            {
                name: "中卫市",
                id: 3399,
                upid: 3372,
                sub: [
                    {
                        name: "市辖区",
                        id: 3400,
                        upid: 3399
                    },
                    {
                        name: "沙坡头区",
                        id: 3401,
                        upid: 3399
                    },
                    {
                        name: "中宁县",
                        id: 3402,
                        upid: 3399
                    },
                    {
                        name: "海原县",
                        id: 3403,
                        upid: 3399
                    }
                ]
            }
        ]
    },
    {
        name: "新疆",
        id: 3404,
        upid: 0,
        sub: [
            {
                name: "乌鲁木齐市",
                id: 3405,
                upid: 3404,
                sub: [
                    {
                        name: "市辖区",
                        id: 3406,
                        upid: 3405
                    },
                    {
                        name: "天山区",
                        id: 3407,
                        upid: 3405
                    },
                    {
                        name: "沙依巴克区",
                        id: 3408,
                        upid: 3405
                    },
                    {
                        name: "新市区",
                        id: 3409,
                        upid: 3405
                    },
                    {
                        name: "水磨沟区",
                        id: 3410,
                        upid: 3405
                    },
                    {
                        name: "头屯河区",
                        id: 3411,
                        upid: 3405
                    },
                    {
                        name: "达坂城区",
                        id: 3412,
                        upid: 3405
                    },
                    {
                        name: "东山区",
                        id: 3413,
                        upid: 3405
                    },
                    {
                        name: "乌鲁木齐县",
                        id: 3414,
                        upid: 3405
                    }
                ]
            },
            {
                name: "克拉玛依市",
                id: 3415,
                upid: 3404,
                sub: [
                    {
                        name: "市辖区",
                        id: 3416,
                        upid: 3415
                    },
                    {
                        name: "独山子区",
                        id: 3417,
                        upid: 3415
                    },
                    {
                        name: "克拉玛依区",
                        id: 3418,
                        upid: 3415
                    },
                    {
                        name: "白碱滩区",
                        id: 3419,
                        upid: 3415
                    },
                    {
                        name: "乌尔禾区",
                        id: 3420,
                        upid: 3415
                    }
                ]
            },
            {
                name: "克拉玛依市吐鲁番地区",
                id: 3421,
                upid: 3404,
                sub: [
                    {
                        name: "吐鲁番市",
                        id: 3422,
                        upid: 3421
                    },
                    {
                        name: "鄯善县",
                        id: 3423,
                        upid: 3421
                    },
                    {
                        name: "托克逊县",
                        id: 3424,
                        upid: 3421
                    }
                ]
            },
            {
                name: "哈密地区",
                id: 3425,
                upid: 3404,
                sub: [
                    {
                        name: "哈密市",
                        id: 3426,
                        upid: 3425
                    },
                    {
                        name: "巴里坤哈萨克自治县",
                        id: 3427,
                        upid: 3425
                    },
                    {
                        name: "伊吾县",
                        id: 3428,
                        upid: 3425
                    }
                ]
            },
            {
                name: "昌吉回族自治州",
                id: 3429,
                upid: 3404,
                sub: [
                    {
                        name: "昌吉市",
                        id: 3430,
                        upid: 3429
                    },
                    {
                        name: "阜康市",
                        id: 3431,
                        upid: 3429
                    },
                    {
                        name: "米泉市",
                        id: 3432,
                        upid: 3429
                    },
                    {
                        name: "呼图壁县",
                        id: 3433,
                        upid: 3429
                    },
                    {
                        name: "玛纳斯县",
                        id: 3434,
                        upid: 3429
                    },
                    {
                        name: "奇台县",
                        id: 3435,
                        upid: 3429
                    },
                    {
                        name: "吉木萨尔县",
                        id: 3436,
                        upid: 3429
                    },
                    {
                        name: "木垒哈萨克自治县",
                        id: 3437,
                        upid: 3429
                    }
                ]
            },
            {
                name: "博尔塔拉蒙古自治州",
                id: 3438,
                upid: 3404,
                sub: [
                    {
                        name: "博乐市",
                        id: 3439,
                        upid: 3438
                    },
                    {
                        name: "精河县",
                        id: 3440,
                        upid: 3438
                    },
                    {
                        name: "温泉县",
                        id: 3441,
                        upid: 3438
                    }
                ]
            },
            {
                name: "巴音郭楞蒙古自治州",
                id: 3442,
                upid: 3404,
                sub: [
                    {
                        name: "库尔勒市",
                        id: 3443,
                        upid: 3442
                    },
                    {
                        name: "轮台县",
                        id: 3444,
                        upid: 3442
                    },
                    {
                        name: "尉犁县",
                        id: 3445,
                        upid: 3442
                    },
                    {
                        name: "若羌县",
                        id: 3446,
                        upid: 3442
                    },
                    {
                        name: "且末县",
                        id: 3447,
                        upid: 3442
                    },
                    {
                        name: "焉耆回族自治县",
                        id: 3448,
                        upid: 3442
                    },
                    {
                        name: "和静县",
                        id: 3449,
                        upid: 3442
                    },
                    {
                        name: "和硕县",
                        id: 3450,
                        upid: 3442
                    },
                    {
                        name: "博湖县",
                        id: 3451,
                        upid: 3442
                    }
                ]
            },
            {
                name: "阿克苏地区",
                id: 3452,
                upid: 3404,
                sub: [
                    {
                        name: "阿克苏市",
                        id: 3453,
                        upid: 3452
                    },
                    {
                        name: "温宿县",
                        id: 3454,
                        upid: 3452
                    },
                    {
                        name: "库车县",
                        id: 3455,
                        upid: 3452
                    },
                    {
                        name: "沙雅县",
                        id: 3456,
                        upid: 3452
                    },
                    {
                        name: "新和县",
                        id: 3457,
                        upid: 3452
                    },
                    {
                        name: "拜城县",
                        id: 3458,
                        upid: 3452
                    },
                    {
                        name: "乌什县",
                        id: 3459,
                        upid: 3452
                    },
                    {
                        name: "阿瓦提县",
                        id: 3460,
                        upid: 3452
                    },
                    {
                        name: "柯坪县",
                        id: 3461,
                        upid: 3452
                    }
                ]
            },
            {
                name: "克孜勒苏柯尔克孜自治州",
                id: 3462,
                upid: 3404,
                sub: [
                    {
                        name: "阿图什市",
                        id: 3463,
                        upid: 3462
                    },
                    {
                        name: "阿克陶县",
                        id: 3464,
                        upid: 3462
                    },
                    {
                        name: "阿合奇县",
                        id: 3465,
                        upid: 3462
                    },
                    {
                        name: "乌恰县",
                        id: 3466,
                        upid: 3462
                    }
                ]
            },
            {
                name: "喀什地区",
                id: 3467,
                upid: 3404,
                sub: [
                    {
                        name: "喀什市",
                        id: 3468,
                        upid: 3467
                    },
                    {
                        name: "疏附县",
                        id: 3469,
                        upid: 3467
                    },
                    {
                        name: "疏勒县",
                        id: 3470,
                        upid: 3467
                    },
                    {
                        name: "英吉沙县",
                        id: 3471,
                        upid: 3467
                    },
                    {
                        name: "泽普县",
                        id: 3472,
                        upid: 3467
                    },
                    {
                        name: "莎车县",
                        id: 3473,
                        upid: 3467
                    },
                    {
                        name: "叶城县",
                        id: 3474,
                        upid: 3467
                    },
                    {
                        name: "麦盖提县",
                        id: 3475,
                        upid: 3467
                    },
                    {
                        name: "岳普湖县",
                        id: 3476,
                        upid: 3467
                    },
                    {
                        name: "伽师县",
                        id: 3477,
                        upid: 3467
                    },
                    {
                        name: "巴楚县",
                        id: 3478,
                        upid: 3467
                    },
                    {
                        name: "塔什库尔干塔吉克自治县",
                        id: 3479,
                        upid: 3467
                    }
                ]
            },
            {
                name: "和田地区",
                id: 3480,
                upid: 3404,
                sub: [
                    {
                        name: "和田市",
                        id: 3481,
                        upid: 3480
                    },
                    {
                        name: "和田县",
                        id: 3482,
                        upid: 3480
                    },
                    {
                        name: "墨玉县",
                        id: 3483,
                        upid: 3480
                    },
                    {
                        name: "皮山县",
                        id: 3484,
                        upid: 3480
                    },
                    {
                        name: "洛浦县",
                        id: 3485,
                        upid: 3480
                    },
                    {
                        name: "策勒县",
                        id: 3486,
                        upid: 3480
                    },
                    {
                        name: "于田县",
                        id: 3487,
                        upid: 3480
                    },
                    {
                        name: "民丰县",
                        id: 3488,
                        upid: 3480
                    }
                ]
            },
            {
                name: "伊犁哈萨克自治州",
                id: 3489,
                upid: 3404,
                sub: [
                    {
                        name: "伊宁市",
                        id: 3490,
                        upid: 3489
                    },
                    {
                        name: "奎屯市",
                        id: 3491,
                        upid: 3489
                    },
                    {
                        name: "伊宁县",
                        id: 3492,
                        upid: 3489
                    },
                    {
                        name: "察布查尔锡伯自治县",
                        id: 3493,
                        upid: 3489
                    },
                    {
                        name: "霍城县",
                        id: 3494,
                        upid: 3489
                    },
                    {
                        name: "巩留县",
                        id: 3495,
                        upid: 3489
                    },
                    {
                        name: "新源县",
                        id: 3496,
                        upid: 3489
                    },
                    {
                        name: "昭苏县",
                        id: 3497,
                        upid: 3489
                    },
                    {
                        name: "特克斯县",
                        id: 3498,
                        upid: 3489
                    },
                    {
                        name: "尼勒克县",
                        id: 3499,
                        upid: 3489
                    }
                ]
            },
            {
                name: "塔城地区",
                id: 3500,
                upid: 3404,
                sub: [
                    {
                        name: "塔城市",
                        id: 3501,
                        upid: 3500
                    },
                    {
                        name: "乌苏市",
                        id: 3502,
                        upid: 3500
                    },
                    {
                        name: "额敏县",
                        id: 3503,
                        upid: 3500
                    },
                    {
                        name: "沙湾县",
                        id: 3504,
                        upid: 3500
                    },
                    {
                        name: "托里县",
                        id: 3505,
                        upid: 3500
                    },
                    {
                        name: "裕民县",
                        id: 3506,
                        upid: 3500
                    },
                    {
                        name: "和布克赛尔蒙古自治县",
                        id: 3507,
                        upid: 3500
                    }
                ]
            },
            {
                name: "阿勒泰地区",
                id: 3508,
                upid: 3404,
                sub: [
                    {
                        name: "阿勒泰市",
                        id: 3509,
                        upid: 3508
                    },
                    {
                        name: "布尔津县",
                        id: 3510,
                        upid: 3508
                    },
                    {
                        name: "富蕴县",
                        id: 3511,
                        upid: 3508
                    },
                    {
                        name: "福海县",
                        id: 3512,
                        upid: 3508
                    },
                    {
                        name: "哈巴河县",
                        id: 3513,
                        upid: 3508
                    },
                    {
                        name: "青河县",
                        id: 3514,
                        upid: 3508
                    },
                    {
                        name: "吉木乃县",
                        id: 3515,
                        upid: 3508
                    }
                ]
            },
            {
                name: "省直辖行政单位",
                id: 3516,
                upid: 3404,
                sub: [
                    {
                        name: "石河子市",
                        id: 3517,
                        upid: 3516
                    },
                    {
                        name: "阿拉尔市",
                        id: 3518,
                        upid: 3516
                    },
                    {
                        name: "图木舒克市",
                        id: 3519,
                        upid: 3516
                    },
                    {
                        name: "五家渠市",
                        id: 3520,
                        upid: 3516
                    }
                ]
            }
        ]
    },
    {
        name: "台湾",
        id: 3521,
        upid: 0,
        sub: [
            {
                name: "台湾",
                id: 46178,
                upid: 3521,
                sub: [
                    {
                        name: "台湾",
                        id: 46179,
                        upid: 46178
                    }
                ]
            }
        ]
    },
    {
        name: "香港",
        id: 3522,
        upid: 0,
        sub: [
            {
                name: "香港",
                id: 46176,
                upid: 3522,
                sub: [
                    {
                        name: "香港",
                        id: 46177,
                        upid: 46176
                    }
                ]
            }
        ]
    },
    {
        name: "澳门",
        id: 3523,
        upid: 0,
        sub: [
            {
                name: "澳门",
                id: 46180,
                upid: 3523,
                sub: [
                    {
                        name: "澳门",
                        id: 46181,
                        upid: 46180
                    }
                ]
            }
        ]
    }
];
}(Zepto);
// jshint ignore: end

/* jshint unused:false*/

+ function($) {
    "use strict";
    var city_ids = [];
    var city_id;
    var district_id;
    var format = function(data) {
        var result = [];
        for(var i=0;i<data.length;i++) {
            var d = data[i];
            if(d.name === "请选择") continue;
            result.push(d.name);
        }
        if(result.length) return result;
        return [""];
    };

    var sub = function(data) {
        if(!data.sub) return [""];
        return format(data.sub);
    };

    var getCities = function(d) {
        for(var i=0;i< raw.length;i++) {
            if(raw[i].name === d) {
                return sub(raw[i]);
            }
        }
        return [""];
    };

    var getDistricts = function(p, c) {
        for(var i=0;i< raw.length;i++) {
            if(raw[i].name === p) {
                for(var j=0;j< raw[i].sub.length;j++) {
                    if(raw[i].sub[j].name === c) {
                        city_id = raw[i].sub[j].id;
                        return sub(raw[i].sub[j]);
                    }
                }
            }
        }
        return [""];
    };
    //  获取地区ID
    var getDistrictId = function(p, c, d) {
        for(var i=0;i< raw.length;i++) {
            if(raw[i].name === p) {
                for(var j=0;j< raw[i].sub.length;j++) {
                    if(raw[i].sub[j].name === c) {
                        for (var s=0; s<raw[i].sub[j].sub.length;s++) {
                            if(raw[i].sub[j].sub[s].name === d) {
                                district_id = raw[i].sub[j].sub[s].id;
                                return sub(raw[i].sub[j].sub[s]);
                            }
                        }
                    }
                }
            }
        }
        return [""];
    };

    var raw = $.smConfig.rawCitiesData;
    var provinces = raw.map(function(d) {
        return d.name;
    });
    var initCities = sub(raw[0]);
    var initDistricts = [""];

    var currentProvince = provinces[0];
    var currentCity = initCities[0];
    var currentDistrict = initDistricts[0];

    var t;
    var defaults = {

        cssClass: "city-picker",
        rotateEffect: false,  //为了性能

        onChange: function (picker, values, displayValues) {
            var newProvince = picker.cols[0].value;
            var newCity;
            if(newProvince !== currentProvince) {
                // 如果Province变化，节流以提高reRender性能
                clearTimeout(t);

                t = setTimeout(function(){
                    var newCities = getCities(newProvince);
                    newCity = newCities[0];
                    var newDistricts = getDistricts(newProvince, newCity);
                    picker.cols[1].replaceValues(newCities);
                    picker.cols[2].replaceValues(newDistricts);
                    currentProvince = newProvince;
                    currentCity = newCity;
                    picker.updateValue();
                }, 200);
                return;
            }
            newCity = picker.cols[1].value;
            if(newCity !== currentCity) {
                picker.cols[2].replaceValues(getDistricts(newProvince, newCity));
                currentCity = newCity;
                picker.updateValue();
            }
            //by mercury 20171127
            var displayValue    = picker.displayValue.filter(function (value) {
                return value != '';
            }).toString();

            getDistrictId(newProvince, newCity, displayValues[2]);
            //设置所在省份的值
            var province_id = 0;
            picker.displayValue.filter(function (value) {
                for (var i in raw) {
                   if (raw[i].name == value) {
                       province_id = raw[i].id;
                       break;
                   }
                }
                return;
            });
            $("input[name='province_id']").val(province_id);
            $("input[name='city_id']").val(city_id);
            $("input[name='district_id']").val(district_id);
            $("#city-picker").html(displayValue);
            if (picker.cols[2].value != currentDistrict) {
                $("#town-picker").html("请选择");
                $("input[name='town_id']").val('');
            }
        },

        cols: [
        {
            textAlign: 'center',
            values: provinces,
            cssClass: "col-province"
        },
        {
            textAlign: 'center',
            values: initCities,
            cssClass: "col-city"
        },
        {
            textAlign: 'center',
            values: initDistricts,
            cssClass: "col-district"
        }
        ]
    };

    $.fn.cityPicker = function(params) {
        return this.each(function() {
            if(!this) return;
            var p = $.extend(defaults, params);
            //计算value
            if (p.value) {
                $(this).val(p.value.join(' '));
            } else {
                var val = $(this).val();
                val && (p.value = val.split(' '));
            }
            if (p.value) {
                //p.value = val.split(" ");
                if(p.value[0]) {
                    currentProvince = p.value[0];
                    p.cols[1].values = getCities(p.value[0]);
                }
                if(p.value[1]) {
                    currentCity = p.value[1];
                    p.cols[2].values = getDistricts(p.value[0], p.value[1]);
                } else {
                    p.cols[2].values = getDistricts(p.value[0], p.cols[1].values[0]);
                }
                !p.value[2] && (p.value[2] = '');
                currentDistrict = p.value[2];
            }
            $(this).picker(p);
        });
    };

}(Zepto);
