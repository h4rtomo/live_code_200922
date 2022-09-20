import unittest


def looping(n):
    list_string = []
    for i in range(1, n + 1):
        if i % 3 == 0 and i % 5 == 0:
            list_string.append('Frontend Backend')
        elif i % 3 == 0:
            list_string.append('Frontend')
        elif i % 5 == 0:
            list_string.append('Backend')
        else:
            list_string.append(str(i))

    return ",".join(list_string)


class TestLoping(unittest.TestCase):
    def test_looping(self):
        self.assertEqual(
            looping(10), "1,2,Frontend,4,Backend,Frontend,7,8,Frontend,Backend")
        self.assertEqual(
            looping(50), "1,2,Frontend,4,Backend,Frontend,7,8,Frontend,Backend,11,Frontend,13,14,Frontend Backend,16,17,Frontend,19,Backend,Frontend,22,23,Frontend,Backend,26,Frontend,28,29,Frontend Backend,31,32,Frontend,34,Backend,Frontend,37,38,Frontend,Backend,41,Frontend,43,44,Frontend Backend,46,47,Frontend,49,Backend")


if __name__ == '__main__':
    unittest.main()
